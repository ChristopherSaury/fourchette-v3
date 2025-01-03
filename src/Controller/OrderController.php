<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\FVOrder;
use App\Entity\FVOrderDetail;
use App\Form\FVOrderType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {
        $addresses = $this->getUser()->getFVAddresses();

        if (count($addresses) == 0) {
            return $this->redirectToRoute('app_account_addresses_form');
        }

        $form = $this->createForm(FVOrderType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('app_order_summary')
        ]);

        return $this->render('order/index.html.twig', [
            'deliverForm' => $form->createView(),
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'app_order_summary')]
    public function add(Request $request, Cart $cart, EntityManagerInterface $entityManager)
    {

        if ($request->getMethod() != 'POST') {
            return $this->redirectToRoute('app_cart');
        }

        $dishes = $cart->getCart();

        $form = $this->createForm(FVOrderType::class, null, [
            'addresses' => $this->getUser()->getFVAddresses(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ajout entité FVOrder
            $order = new FVOrder;
            $order->setUser($this->getUser());
            $order->setCreatedAt(new DateTime());
            $order->setState(1);
            $order->setCarrierName($form->get('carriers')->getData()->getName());
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());

            $addressObj = $form->get('addresses')->getData();

            $addresses = '';
            $addresses = $addressObj->getLastname() . ' ' . $addressObj->getFirstname() . '</br>';
            $addresses .= $addressObj->getAddress() . ' ';
            $addresses .= $addressObj->getPostal() . ', ' . $addressObj->getCity() . '</br> ';
            $addresses .= $addressObj->getCountry() . ' </br>';
            $addresses .= $addressObj->getPhone();

            $order->setDelivery($addresses);

            //ajout des Détail de commande entité FVOrderDetail

            foreach ($dishes as $dish) {
                $orderDetail = new FVOrderDetail;
                $orderDetail->setDishName($dish['object']->getName());
                $orderDetail->setDishImage($dish['object']->getImage());
                $orderDetail->setDishQuantity($dish['qty']);
                $orderDetail->setDishPrice($dish['object']->getPrice());
                $orderDetail->setDishTva($dish['object']->getTva());
                $order->addFVOrderDetail($orderDetail);
            }

            $entityManager->persist($order);
            $entityManager->flush();
        }
        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $dishes,
            'order' => $order,
            'cartQty' => $cart->getFullCartQty(),
            'totalWt' => $cart->getTotalWt()
        ]);
    }
}
