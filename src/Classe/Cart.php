<?php

namespace App\Classe;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(private RequestStack $requestStack)
    {
        
    }
    public function add($dish, int $qty){
        $cart = $this->requestStack->getSession()->get('cart');

        if(isset($cart[$dish->getId()])){
            $cart[$dish->getId()] = [
                'object' => $dish,
                'qty' => $cart[$dish->getId()]['qty'] + $qty
            ];
        }else{
            $cart[$dish->getId()] = [
                'object' => $dish,
                'qty' => $qty
            ];
        }


        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function getCart(){
        return $this->requestStack->getSession()->get('cart');
    }

    public function decrease($id){
        $cart = $this->requestStack->getSession()->get('cart');

        if($cart[$id]['qty'] > 1){
            $cart[$id]['qty'] = $cart[$id]['qty'] - 1;
        }else{
            unset($cart[$id]);
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function removeDish($id){
        $cart = $this->requestStack->getSession()->get('cart');

        unset($cart[$id]);

        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function getFullCartQty(){
        $cart = $this->requestStack->getSession()->get('cart');
        $quantity = 0;

        if(!isset($cart)){
            return $quantity;
        }

        foreach ($cart as $item) {
            $quantity = $quantity + $item['qty'];
        }
        return $quantity;
    }

    public function getTotalWt(){
        $cart = $this->requestStack->getSession()->get('cart');
        $price = 0;

        if(!isset($cart)){
            return $price;
        }

        foreach ($cart as $item) {
            $price = $price +  ($item['object']->getPriceWt() *  $item['qty']);
        }
        return $price;
    }

    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }
}