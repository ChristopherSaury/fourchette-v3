<?php

namespace App\Controller\Admin;

use App\Entity\FVUser;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

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
            ChoiceField::new('roles', 'Permission')->setHelp('Vous pouvez choisir le (ou les) rôle(s) de cet utilisateur')
            ->setChoices([
                'ROLE_USER' => 'ROLE_USER',
                'ROLE_ADMIN' => 'ROLE_ADMIN'
            ])->allowMultipleChoices(),
        ];
    }
}
