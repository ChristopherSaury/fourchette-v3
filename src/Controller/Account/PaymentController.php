<?php

namespace App\Controller\Account;

use App\Classe\Cart;
use App\Repository\FVOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
  private $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }
  #[Route('/commande/paiement/{id_order}', name: 'app_payment')]
  public function index($id_order, FVOrderRepository $fVOrderRepository): Response
  {
    Stripe::setApiKey($_ENV["STRIPE_SECRET_KEY"]);

    $order = $fVOrderRepository->findOneBy([
      'user' => $this->getUser(),
      'id' => $id_order,
      'state' => 1
    ]);

    if (!$order) {
      return $this->redirectToRoute('app_static_home');
    }

    $dish_for_stripe = [];

    foreach ($order->getFVOrderDetails() as $dish) {
      $dish_for_stripe[] = [
        'price_data' => [
          'currency' => 'eur',
          'unit_amount' => number_format($dish->getDishPriceWt() * 100, 0, '', ''),
          'product_data' => [
            'name' => $dish->getDishName(),
            'images' => [
              $_ENV["DOMAIN"] . '/image/uploads/' . $dish->getDishImage()
            ]
          ]
        ],
        'quantity' => $dish->getDishQuantity(),
      ];
    }


    $dish_for_stripe[] = [
      'price_data' => [
        'currency' => 'eur',
        'unit_amount' => number_format($order->getCarrierPrice() * 100, 0, '', ''),
        'product_data' => [
          'name' => 'Formule de livraison : ' . $order->getCarrierName(),
        ]
      ],
      'quantity' => 1,
    ];

    $checkout_session = Session::create([
      'customer_email' => $this->getUser()->getEmail(),
      'line_items' => [[
        $dish_for_stripe
      ]],
      'mode' => 'payment',
      'success_url' => $_ENV["DOMAIN"] . '/commande/merci/{CHECKOUT_SESSION_ID}',
      'cancel_url' => $_ENV["DOMAIN"] . '/mon-panier/annulation',
    ]);

    $order->setStripeSessionId($checkout_session->id);
    $this->entityManager->flush();


    return $this->redirect($checkout_session->url);
  }

  #[Route('/commande/merci/{stripe_session_id}', name: 'app_payment_success')]
  public function success($stripe_session_id, FVOrderRepository $fVOrderRepository, Cart $cart): Response
  {
    $order = $fVOrderRepository->findOneBy([
      'stripe_session_id' => $stripe_session_id,
      'user' => $this->getUser()
    ]);

    if (!$order) {
      return $this->redirectToRoute('app_static_home');
    }

    if ($order->getState() == 1) {
      $order->setState(2);
      $cart->remove();
      $this->entityManager->flush();
    }

    return $this->render('payment/success.html.twig', [
      'order' => $order,
    ]);
  }
}
