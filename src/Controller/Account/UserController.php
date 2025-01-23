<?php

namespace App\Controller\Account;

use App\Form\FVUserModifyFormType;
use Mailjet\Client;
use Mailjet\Resources;
use App\Repository\FVUserRepository;
use App\Repository\FVOrderRepository;
use App\Repository\FVAddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(FVOrderRepository $fVOrderRepository): Response
    {
        $orders = $fVOrderRepository->findBy([
            'user' => $this->getUser(),
            'state' => [2, 3, 4, 5, 6],
        ], ['createdAt' => 'DESC']);

        return $this->render('account/index.html.twig', [
            'orders' => $orders
        ]);
    }
    #[Route('/compte/modifier/utilisateur', name: 'app_account_modify')]
    public function modify(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(FVUserModifyFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success-modify-user', 'Données utilisateurs mise à jours avec succès');
            return $this->redirectToRoute('app_account_modify');
        }

        return $this->render('account/user/index.html.twig',[
            'userFormModify' => $form->createView()
        ]);

    }

    #[Route('/compte/supprimer/user/{id}', name: 'app_account_delete')]
    public function delete(
        $id,
        Security $security,
        EntityManagerInterface $entityManager,
        FVUserRepository $fVUserRepository,
        FVAddressRepository $fVAddressRepository
    ): Response {
        if ($this->getUser()->getId() != $id) {
            return $this->redirectToRoute('app_account');
        }
        $user = $fVUserRepository->findOneById($id);
        if (!$user) {
            return $this->redirectToRoute('app_account');
        }
        $addresses = $fVAddressRepository->findByUser($user);
        if (count($addresses) > 0) {
            foreach ($addresses as $address) {
                $entityManager->remove($address);
                $entityManager->flush();
            };
        }

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
                        'TemplateID' => 6651115,
                        'TemplateLanguage' => true,
                        'Subject' => "Confirmation de suppression de compte la-fourchette-victorieuse.fr",
                    ]
                ]
            ];
            $mj->post(Resources::$Email, ['body' => $body]);

        $entityManager->remove($user);
        $entityManager->flush();


        $response = $this->redirectToRoute('app_static_home');
        $security->logout(false);
        return $response;
    }
}
