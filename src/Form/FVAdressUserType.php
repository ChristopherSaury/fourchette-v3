<?php

namespace App\Form;

use App\Entity\FVAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('postal', ChoiceType::class,[
                'choices' => [
                    '75001' => '75001','75002' => '75002', '75003' => '75003',
                    '75004' => '75004','75005' => '75005','75006' => '75006',
                    '75007' => '75007','75008' => '75008','75009' => '75009',
                    '75010' => '75010','75011' => '75011','75012' => '75012',
                    '75013' => '75013','75014' => '75014','75015' => '75015',
                    '75016' => '75016','75017' => '75017','75018' => '75018',
                    '75019' => '75019','75020' => '75020',

                ]
            ])
            ->add('city', TextType::class,[
            'data' => 'Paris',
            'label' => 'Ville :',
            'disabled' => true,
            ])
            ->add('country', CountryType::class,[
            'data' => 'FR',
            'label' => 'Pays :',
            'disabled' => true,
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
