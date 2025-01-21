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
        return $this->render('staticPage/home/index.html.twig');
    }
    #[Route('/foire-aux-questions', name: 'app_static_faq')]
    public function faq(): Response
    {
        return $this->render('staticPage/faq/index.html.twig');
    }
    #[Route('/conditions-generales-utilisation', name:'app_static_agree_terms')]
    public function agreeTerms(): Response
    {
        return $this->render('staticPage/terms_of_use/index.html.twig');
    }
    #[Route('/politique-de-confidentialite', name:'app_static_data_policy')]
    public function dataPolicy(): Response
    {
        return $this->render('staticPage/data_policy/index.html.twig');
    }
    #[Route('/a-propos', name:'app_static_about')]
    public function aboutUs(): Response
    {
        return $this->render('staticPage/about_us/index.html.twig');
    }
    #[Route('/offre-du-developpeur', name:'app_static_offer')]
    public function offer(): Response
    {
        return $this->render('staticPage/offer/index.html.twig');
    }       
}
