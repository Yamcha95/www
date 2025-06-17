<?php

namespace App\Controller;

use App\Entity\Mdp;
use App\Form\MdpForm;
use App\Repository\MdpRepository;
use App\Service\EncryptionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/mdp')]
final class MdpController extends AbstractController
{
    #[Route(name: 'app_mdp_index', methods: ['GET'])]
    public function index(MdpRepository $mdpRepository, EncryptionService $encryptionService): Response
    {
        $user = $this->getUser();
        $mdps = $mdpRepository->findBy(['user' => $user]);

        // Générer un tableau avec les mots de passe déchiffrés séparément
        $decryptedMdps = [];
        foreach ($mdps as $mdp) {
            try {
                $decryptedPassword = $encryptionService->decrypt($mdp->getMdp());
            } catch (\Exception $e) {
                $decryptedPassword = 'Erreur de déchiffrement';
            }
            $decryptedMdps[] = [
                'entity' => $mdp,
                'decrypted' => $decryptedPassword
            ];
        }

        return $this->render('mdp/index.html.twig', [
            'mdps' => $decryptedMdps,
        ]);
    }

    #[Route('/new', name: 'app_mdp_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EncryptionService $encryptionService): Response
    {
        $mdp = new Mdp();
        $form = $this->createForm(MdpForm::class, $mdp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mdp->setUser($this->getUser());
            $mdp->setMdp($encryptionService->encrypt($mdp->getMdp()));

            $entityManager->persist($mdp);
            $entityManager->flush();

            return $this->redirectToRoute('app_mdp_index');
        }

        return $this->render('mdp/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mdp_show', methods: ['GET'])]
    public function show(Mdp $mdp, EncryptionService $encryptionService): Response
    {
        $this->denyAccessUnlessGranted('view', $mdp);

        try {
            $decrypted = $encryptionService->decrypt($mdp->getMdp());
        } catch (\Exception $e) {
            $decrypted = 'Erreur de déchiffrement';
        }

        return $this->render('mdp/show.html.twig', [
            'mdp' => $mdp,
            'decrypted' => $decrypted,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mdp_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mdp $mdp, EntityManagerInterface $entityManager, EncryptionService $encryptionService): Response
    {
        $this->denyAccessUnlessGranted('edit', $mdp);

        try {
            $plaintext = $encryptionService->decrypt($mdp->getMdp());
            $mdp->setMdp($plaintext);
        } catch (\Exception $e) {
            $mdp->setMdp('');
        }

        $form = $this->createForm(MdpForm::class, $mdp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mdp->setMdp($encryptionService->encrypt($mdp->getMdp()));
            $entityManager->flush();

            return $this->redirectToRoute('app_mdp_index');
        }

        return $this->render('mdp/edit.html.twig', [
            'form' => $form,
            'mdp' => $mdp,
        ]);
    }

    #[Route('/{id}', name: 'app_mdp_delete', methods: ['POST'])]
    public function delete(Request $request, Mdp $mdp, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('delete', $mdp);

        if ($this->isCsrfTokenValid('delete' . $mdp->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mdp);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mdp_index');
    }
}
