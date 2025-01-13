<?php

namespace App\Controller\Account;

use App\Repository\FVOrderRepository;
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
            'state' => [2,3,4,5,6],
        ], ['createdAt' => 'DESC']);
        
        return $this->render('account/index.html.twig', [
            'orders' => $orders
        ]);
    }
}
