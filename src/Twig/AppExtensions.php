<?php

namespace App\Twig;

use App\Classe\Cart;
use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtensions extends AbstractExtension implements GlobalsInterface
{
    private $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice'])
        ];
    }

    public function formatPrice($number){
        return number_format($number,2,',') . ' €';
    }

    public function getGlobals(): array
    {
        return[
            'getFullCartQty' => $this->cart->getFullCartQty(),
        ];
    }
}