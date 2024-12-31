<?php

namespace App\Controller\Admin;

use App\Entity\FVCarrier;
use App\Entity\FVCategory;
use App\Entity\FVDish;
use App\Entity\FVOrder;
use App\Entity\FVUser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(FVUserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

       
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('LFV Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-users', FVUser::class);
        yield MenuItem::linkToCrud('Catégories', 'fa-solid fa-list', FVCategory::class);
        yield MenuItem::linkToCrud('Plats', 'fa-solid fa-utensils', FVDish::class);
        yield MenuItem::linkToCrud('Transporteurs', 'fa-solid fa-truck', FVCarrier::class);
        yield MenuItem::linkToCrud('Commandes', 'fa-solid fa-box', FVOrder::class);
    }
}
