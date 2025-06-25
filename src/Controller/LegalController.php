<?php

// src/Controller/LegalController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{
    #[Route('/conditions-utilisation', name: 'conditions_utilisation')]
    public function conditions(): Response
    {
        return $this->render('legal/conditions.html.twig');
    }

    #[Route('/mentions-legales', name: 'mentions_legales')]
    public function mentions(): Response
    {
        return $this->render('legal/mentions.html.twig');
    }
}
