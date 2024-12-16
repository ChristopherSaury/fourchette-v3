<?php

namespace App\Controller\Admin;

use App\Entity\FVUser;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FVUserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FVUser::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs');
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email')->onlyOnIndex(),
            TextField::new('firstname','Prénom'),
            TextField::new('lastname','Nom'),
        ];
    }
}
