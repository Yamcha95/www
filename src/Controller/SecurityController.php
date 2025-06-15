<?php

// src/Controller/SecurityController.php
namespace App\Controller;

use App\Service\LoginRateLimiter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, LoginRateLimiter $limiter): Response
    {
        if (!$limiter->isAllowed()) {
            $remaining = $limiter->getRemainingLockTime();

            if ($remaining > 0) {
                $this->addFlash('error', 'Trop de tentatives. Réessaie dans ' . ceil($remaining / 60) . ' minute(s).');
            } else {
                $this->addFlash('error', 'Veuillez attendre 10 secondes entre chaque tentative.');
            }

            return $this->redirectToRoute('app_login');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode est interceptée par Symfony.');
    }
}

