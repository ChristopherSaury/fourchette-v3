<?php

namespace App\Controller\Admin;

use App\Entity\FVCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;

class FVCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FVCategory::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégories');
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name','Nom')->setHelp('Nom de la catégorie'),
            SlugField::new('slug','URL')->setTargetFieldName('name')->setHelp('URL de la catégorie générée automatiquement')
        ];
    }
}
