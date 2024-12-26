<?php

namespace App\Controller\Account;

use App\Form\PasswordFVUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordController extends AbstractController
{
    #[Route('/compte/modifier-mot-de-passe', name: 'app_account_update_psw')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(PasswordFVUserType::class, $user, [
            'passwordHasher' => $passwordHasher
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            $this->addFlash('success-psw', 'Mot de passe mis à jour avec succès');

            return $this->redirectToRoute('app_account_update_psw');
        }
        return $this->render('account/password/index.html.twig',[
            'psw_update' => $form
        ]);
    }
}
