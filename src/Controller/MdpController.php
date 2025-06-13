<?php

namespace App\Controller;

use App\Entity\Mdp;
use App\Form\MdpForm;
use App\Repository\MdpRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/mdp')]
final class MdpController extends AbstractController
{
    #[Route(name: 'app_mdp_index', methods: ['GET'])]
    public function index(MdpRepository $mdpRepository): Response
    {
        return $this->render('mdp/index.html.twig', [
            'mdps' => $mdpRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mdp_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
    $mdp = new Mdp();
    $form = $this->createForm(MdpForm::class, $mdp);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // ✅ Associer l'utilisateur connecté
        $mdp->setUser($this->getUser());

        $entityManager->persist($mdp);
        $entityManager->flush();

        return $this->redirectToRoute('app_mdp_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('mdp/new.html.twig', [
        'mdp' => $mdp,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_mdp_show', methods: ['GET'])]
    public function show(Mdp $mdp): Response
    {
        return $this->render('mdp/show.html.twig', [
            'mdp' => $mdp,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mdp_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mdp $mdp, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MdpForm::class, $mdp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mdp_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mdp/edit.html.twig', [
            'mdp' => $mdp,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mdp_delete', methods: ['POST'])]
    public function delete(Request $request, Mdp $mdp, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mdp->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mdp);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mdp_index', [], Response::HTTP_SEE_OTHER);
    }
}
