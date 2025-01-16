<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StaticController extends AbstractController
{
    #[Route('/', name: 'app_static_home')]
    public function index(): Response
    {
        return $this->render('staticPage/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/foire-aux-questions', name: 'app_static_faq')]
    public function faq(): Response
    {
        return $this->render('staticPage/faq/index.html.twig');
    }
}
