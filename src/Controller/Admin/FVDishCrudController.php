<?php

namespace App\Controller\Admin;

use App\Entity\FVDish;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FVDishCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FVDish::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Plat')
            ->setEntityLabelInPlural('Plats');
    }

    public function configureFields(string $pageName): iterable
    {
        $required = true;

        if ($pageName === 'edit') {
            $required = false;
        }
        return [
            TextField::new('name', 'Nom')->setHelp('Nom du plat'),
            SlugField::new('slug', 'URL')->setTargetFieldName('name')->setHelp('URL générée automatiquement'),
            TextEditorField::new('description', 'Description')->setHelp('Description du plat'),
            ImageField::new('image', 'Image')
                ->setHelp('Photo du plat en 600x600')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setBasePath('/image/uploads')->setUploadDir('/public/image/uploads')
                ->setRequired($required),
            NumberField::new('price', 'Prix H.T')->setHelp('Prix du plat H.T sans le sigle €'),
            ChoiceField::new('tva', 'Taux de TVA')->setChoices([
                '5,5%' => '5.5',
                '10%' => '10',
                '20%' => '20'
            ]),
            AssociationField::new('category', 'Catégorie'),
            BooleanField::new('isAvailable', 'Disponible')->setHelp('Le plat est-il disponible ?')

        ];
    }
}
