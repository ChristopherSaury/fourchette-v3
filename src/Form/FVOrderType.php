<?php

namespace App\Form;

use App\Entity\FVAddress;
use App\Entity\FVCarrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FVOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addresses', EntityType::class,[
                'attr' => [
                    'class' => 'orderInput'
                ],
                'label' => 'Choisissez votre adresse de livraison :',
                'required' => true,
                'class' => FVAddress::class,
                'expanded' => true,
                'choices' => $options['addresses'],
                'label_html' => true
            ])
            ->add('carriers', EntityType::class,[
                'attr' => [
                    'class' => 'orderInput'
                ],
                'label' => 'Choisissez votre formule de livraison :',
                'required' => true,
                'class' => FVCarrier::class,
                'expanded' => true,
                'label_html' => true
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-success'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'addresses' => null
        ]);
    }
}
