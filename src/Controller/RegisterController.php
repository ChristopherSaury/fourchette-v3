<?php

namespace App\Controller;

use App\Entity\FVUser;
use App\Form\RegisterFVUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request,EntityManagerInterface $em): Response
    {
        $user = new FVUser; 
        $form = $this->createForm(RegisterFVUserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_account');
        }

        return $this->render('register/index.html.twig',[
            'registerForm' => $form->createView()
        ]);
    }
}
