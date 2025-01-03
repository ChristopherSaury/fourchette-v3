<?php

namespace App\Controller\Account;

use App\Repository\FVOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/compte/commande/detail/{id_order}', name: 'app_order_details')]
    public function index($id_order, FVOrderRepository $fVOrderRepository): Response
    {
        $order = $fVOrderRepository->findOneBy([
            'user' => $this->getUser(),
            'id' => $id_order
        ]);

        if(!$order){
            return $this->redirectToRoute('app_home');
        }
        return $this->render('account/order/index.html.twig', [
            'order' => $order
        ]);
    }
}
