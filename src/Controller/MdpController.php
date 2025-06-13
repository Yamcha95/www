<?php

namespace App\Controller;

use App\Entity\Mdp;
use App\Form\MdpForm;
use App\Repository\MdpRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/mdp')]
final class MdpController extends AbstractController
{
    #[Route(name: 'app_mdp_index', methods: ['GET'])]
    public function index(MdpRepository $mdpRepository): Response
    {
        $user = $this->getUser();
        $mdps = $mdpRepository->findBy(['user' => $user]);

        return $this->render('mdp/index.html.twig', [
            'mdps' => $mdps,
        ]);
    }

    #[Route('/new', name: 'app_mdp_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mdp = new Mdp();
        $form = $this->createForm(MdpForm::class, $mdp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
        if ($mdp->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('mdp/show.html.twig', [
            'mdp' => $mdp,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mdp_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mdp $mdp, EntityManagerInterface $entityManager): Response
    {
        if ($mdp->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

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
        if ($mdp->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$mdp->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mdp);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mdp_index', [], Response::HTTP_SEE_OTHER);
    }
}
