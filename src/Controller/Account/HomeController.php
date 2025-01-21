<?php

namespace App\Controller\Account;

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

class HomeController extends AbstractController
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
