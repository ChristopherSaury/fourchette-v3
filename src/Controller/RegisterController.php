<?php

namespace App\Controller;

use DateTime;
use Mailjet\Client;
use App\Entity\FVUser;
use Mailjet\Resources;
use App\Form\RegisterFVUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = new FVUser;
        $form = $this->createForm(RegisterFVUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCreatedAt(new DateTime());
            $em->persist($user);
            $em->flush();

            $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);

            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => $_ENV['ADMIN_EMAIL'],
                            'Name' => "Saury Christopher"
                        ],
                        'To' => [
                            [
                                'Email' => $user->getEmail(),
                                'Name' => $user->getFirstname()
                            ]
                        ],
                        'TemplateID' => 6646381,
                        'TemplateLanguage' => true,
                        'Subject' => "Bienvenue sur La Fourchette Victorieuse",
                    ]
                ]
            ];
            $mj->post(Resources::$Email, ['body' => $body]);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
