<?php

namespace App\Form;

use App\Entity\FVAddress;
use App\Entity\FVUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FVAdressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('lastname', TextType::class,[
            'label' => 'Nom :',
            'attr' => ['placeholder' => 'ex: Daniel']
        ])
        ->add('firstname', TextType::class,[
            'label' => 'Prénom :',
            'attr' => ['placeholder' => 'ex: Tony']
        ])
            ->add('address', TextType::class,[
            'label' => 'Adresse :',
            'attr' => ['placeholder' => 'Saisissez l\'adresse de livraison']
            ])
            ->add('postal', TextType::class,[
            'label' => 'Code postal :',
            'attr' => ['placeholder' => 'ex: 75107']
            ])
            ->add('city', TextType::class,[
            'label' => 'Ville :',
            'attr' => ['placeholder' => 'ex: Paris']  
            ])
            ->add('country', CountryType::class,[
            'label' => 'Pays :',
            'attr' => ['placeholder' => 'ex: France']
            ])
            ->add('phone', TextType::class,[
            'label' => 'Numéro de téléphone :',
            'attr' => ['placeholder' => 'Saisissez votre numéro de téléphone']
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Sauvegarder',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FVAddress::class,
        ]);
    }
}
