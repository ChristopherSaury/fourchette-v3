<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        $form = $this->createForm(ContactType::class);
        
        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }

    #[Route('/contact/handler', name:'app_contact_handler')]
    public function send(Mail $mail, Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $vars = [
                'lastname' => $form->get('lastname')->getData(),
                'firstname' => $form->get('firstname')->getData(),
                'to_mail' => $form->get('email')->getData(),
                'subject' => $form->get('subject')->getData(),
                'content' => $form->get('content')->getData()
            ];
            $to_name = $form->get('firstname')->getData().' '.$form->get('lastname')->getData();
            $mail->contact($to_name, $form->get('subject')->getData(),'contact.html', $vars);

            $this->addFlash(
                'ct-success',
                'Message envoyé avec succès'
            );
        }
        
        return $this->redirectToRoute('app_contact');
    }
}
