<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ResetPasswordFormType;
use App\Form\ForgotPasswordFormType;
use App\Repository\FVUserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PasswordController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/mot-de-passe-oublie', name: 'app_password')]
    public function index(Request $request, FVUserRepository $fVUserRepository): Response
    {
        $form = $this->createForm(ForgotPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $fVUserRepository->findOneByEmail($email);

            $this->addFlash('success', 'Si votre email existe, vous recevrez un lien pour réinitialiser le mot de passe');

            if ($user) {

                $token = bin2hex(random_bytes(15));
                $user->setToken($token);

                $date = new DateTime();
                $date->modify('+10 minutes');

                $user->setTokenExpireAt($date);
                $this->em->flush();

                $mail = new Mail;
                $subject = 'Réinitialisation mot de passe lafourchettevictorieuse.com';
                $vars = [
                    'link' => $this->generateUrl('app_password_reset', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL)
                ];
                $mail->send($user->getEmail(), $user->getFirstname(), $subject, 'forgotpassword.html', $vars);
            }
        }
        return $this->render('password/index.html.twig', [
            'forgottenPsw' => $form->createView(),
        ]);
    }

    #[Route('/mot-de-passe-oublie/reset/{token}', name: 'app_password_reset')]
    public function reset($token, FVUserRepository $fVUserRepository, Request $request, Mail $notification): Response
    {
        if (!$token) {
            return $this->redirectToRoute('app_password');
        }

        $user = $fVUserRepository->findOneByToken($token);
        $now = new DateTime();

        if (!$user || $now > $user->getTokenExpireAt()) {
            return $this->redirectToRoute('app_password');
        }

        $form = $this->createForm(ResetPasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setToken(null);
            $user->setTokenExpireAt(null);

            $this->em->flush();
            $vars = [
                'firstname' => $user->getFirstname()
            ];
            $notification->send($user->getEmail(), $user->getFirstname(), 'Confirmation de la modification de votre mot de passe', 'password_modif_notification.html', $vars);
            $this->addFlash('success-reset', 'Mot de passe mis à jour avec succès');

            return $this->redirectToRoute('app_login');
        }
        return $this->render('password/reset.html.twig', [
            'resetPasswordForm' => $form->createView()
        ]);
    }
}
