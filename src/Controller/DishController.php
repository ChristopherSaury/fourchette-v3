<?php

namespace App\Controller;

use App\Repository\FVCategoryRepository;
use App\Repository\FVDishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DishController extends AbstractController
{
    #[Route('/carte/plats', name: 'app_dish_all')]
    public function index(FVDishRepository $fVDishRepository)
    {

        $starters = $fVDishRepository->findDishes(1, 3);
        $main_courses = $fVDishRepository->findDishes(2, 3);
        $desserts = $fVDishRepository->findDishes(3, 3);

        return $this->render('dish/index.html.twig', [
            'starters' => $starters,
            'main_courses' => $main_courses,
            'desserts' => $desserts
        ]);
    }

    #[Route('/carte/categorie/{slug}', name: 'app_dish_category')]
    public function dishByCategory(
        $slug,
        FVDishRepository $fVDishRepository,
        FVCategoryRepository $fVCategoryRepository
    ): Response {
        $category = $fVCategoryRepository->findOneBySlug($slug);
        $dishes = $fVDishRepository->findDishBySlug($slug);
        $dish_number = count($dishes);

        return $this->render('dish/dish.html.twig', [
            'category' => $category,
            'dish_number' => $dish_number,
            'dishes' => $dishes
        ]);
    }

    #[Route('/carte/plat/{dish_slug}', name: 'app_dish_details')]
    public function dishDetails($dish_slug, FVDishRepository $fVDishRepository)
    {
        $dish = $fVDishRepository->findOneBySlug($dish_slug);
        return $this->render('dish/dish-details/details.html.twig', [
            'dish' => $dish
        ]);
    }
}
