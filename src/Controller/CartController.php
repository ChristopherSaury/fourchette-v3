<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\FVDishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/mon-panier/{motif}', name: 'app_cart', defaults: ['motif' => null])]
    public function index($motif ,Cart $cart): Response
    {
        if($motif == 'annulation'){
            $this->addFlash(
                'success',
                'Paiement annulé : Vous pouvez mettre à jour votre panier'
            );
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(),
            'totalWt' => $cart->getTotalWt()
        ]);
    }

    #[Route('/cart/add/dish/{id}', name: 'app_cart_add')]
    public function addDishToCart($id, Cart $cart, FVDishRepository $fVDishRepository, Request $request): Response
    {
        $qty = $request->get('qty', 1);
        $dish = $fVDishRepository->findOneById($id);
        $cart->add($dish, $qty);

        $this->addFlash(
            'success',
            'Produit ajouté à votre panier'
        );

        return $this->redirect($request->headers->get('referer'));
    }
    #[Route('/cart/decrease/dish/{id}', name: 'app_cart_decrease')]
    public function decrease($id, Cart $cart): Response
    {
        $cart->decrease($id);

        $this->addFlash(
            'success',
            'Produit retiré de votre panier'
        );

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/dish/{id}', name: 'app_cart_remove')]
    public function remove($id, Cart $cart): Response
    {
        $cart->removeDish($id);

        $this->addFlash(
            'success',
            'Produit retiré de votre panier'
        );

        return $this->redirectToRoute('app_cart');
    }
}
