<?php

namespace App\Controller;

use App\Repository\FVCategoryRepository;
use App\Repository\FVDishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/categorie/carte', name:'app_category_all')]
    public function index(FVDishRepository $fVDishRepository){

        $starters = $fVDishRepository->findDishes(1,3);
        $main_courses = $fVDishRepository->findDishes(2,3);
        $desserts = $fVDishRepository->findDishes(3,3);

        return $this->render('category/index.html.twig',[
            'starters' => $starters,
            'main_courses' => $main_courses,
            'desserts' => $desserts
        ]);
    }

    #[Route('/categorie/{slug}', name: 'app_category')]
    public function dishByCategory($slug, FVDishRepository $fVDishRepository,
    FVCategoryRepository $fVCategoryRepository): Response
    {
        $category = $fVCategoryRepository->findOneBySlug($slug);
        $dishes = $fVDishRepository->findDishBySlug($slug);
        $dish_number = count($dishes);

        return $this->render('category/category.html.twig',[
            'category' => $category,
            'dish_number' => $dish_number,
            'dishes' => $dishes
        ]);
    }

    #[Route('/plat/{dish_slug}', name:'app_category_dish')]
    public function dishDetails($dish_slug, FVDishRepository $fVDishRepository){
        $dish = $fVDishRepository->findOneBySlug($dish_slug);
        return $this->render('category/dish-details/details.html.twig',[
            'dish' => $dish
        ]);
    }
}
