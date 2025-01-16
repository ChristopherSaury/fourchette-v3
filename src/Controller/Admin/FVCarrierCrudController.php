<?php

namespace App\Controller\Admin;

use App\Entity\FVCarrier;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FVCarrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FVCarrier::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Transporteur')
            ->setEntityLabelInPlural('Transporteurs');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom du transporteur')->setHelp('Nom du transporteur'),
            TextareaField::new('description', 'Description du transporteur'),
            NumberField::new('price', 'Prix T.T.C')->setHelp('Prix T.T.C du transporteur sans le sigle €'),
        ];
    }
}
