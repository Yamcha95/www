<?php
namespace App\Controller;

use App\Form\PasswordGeneratorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(PasswordGeneratorType::class);
        $form->handleRequest($request);

        $generatedPassword = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $generatedPassword = $this->generatePassword(
                $data['length'],
                $data['include_uppercase'],
                $data['include_lowercase'],
                $data['include_numbers'],
                $data['include_symbols']
            );
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'password' => $generatedPassword,
        ]);
    }

    private function generatePassword(int $length, bool $uppercase, bool $lowercase, bool $numbers, bool $symbols): string
    {
        $characters = '';
        if ($uppercase) $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($lowercase) $characters .= 'abcdefghijklmnopqrstuvwxyz';
        if ($numbers)   $characters .= '0123456789';
        if ($symbols)   $characters .= '!@#$%^&*()-_=+[]{}<>?/';

        if (empty($characters)) return '';

        $password = '';
        $max = strlen($characters) - 1;

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, $max)];
        }

        return $password;
    }
}
