<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationForm;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticatorInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private EmailVerifier $emailVerifier
    ) {}

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData())
            );
            $token = bin2hex(random_bytes(8));
            $user->setIsVerified($token);
            $entityManager->persist($user);
            $entityManager->flush(); // FLUSH AVANT ENVOI D’EMAIL

//envoie du mail de confirmation

    $url = "http://localhost/confirm-email/$token";

        $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        $mailer = new Mailer($transport);

        $email = (new Email())
            ->from('mehdi@d3v4pp.fr')
            ->to($user->getEmail())
            ->subject('Confirmez votre inscription à StrongBox ✅')
            ->html("
            <h1>Bienvenue, ".$user->getEmail()." !</h1>
            <p>Merci de vous être inscrit.</p>
            <p>Pour activer votre compte, cliquez ici : <a href='$url'>$url</a></p>
        ");

            $mailer->send($email);




            return $this->redirectToRoute('app_check_email');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }



    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        // NE PAS restreindre aux utilisateurs connectés
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $id = $request->get('id');
        if (!$id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->redirectToRoute('app_register');
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('verify_email_error', $e->getReason());

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }

    #[Route('/confirm-email/{token}', name: 'app_confirm_email')]
    public function confirmEmail(string $token, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['isVerified' => $token]);

        if (!$user) {
            return new Response("<h2>Lien de confirmation invalide ou expiré.</h2>");
        }

        $user->setIsVerified('true');
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Votre compte a été activé avec succès !');
        
        return $this->redirectToRoute('app_login');
    }
}