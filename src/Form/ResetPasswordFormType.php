<?php

namespace App\Form;

use App\Entity\FVUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le mot de passe ne peut pas être vide.',
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'max' => 30,
                        'minMessage' => 'Le mot de passe doit contenir au moins 8 caractères.',
                        'maxMessage' => 'Le mot de passe ne peut pas dépasser 30 caractères.',
                    ]),
                    new Assert\Regex([
                        'pattern' => "/[@#$%&€£!*]/",
                        'message' => 'Le mot de passe doit contenir au moins un caractère spécial parmi les suivants : @,#,$,%,&,€,£,!'
                    ])
                ],
                'first_options' => [
                    'label' => 'Votre nouveau mot de passe :',
                    'hash_property_path' => 'password',
                    'attr' => ['placeholder' => 'Choisissez votre nouveau mot de passe']
                ],
                'second_options' => [
                    'label' => 'Confirmez nouveau mot de passe :',
                    'attr' => ['placeholder' => 'Entrez votre Mot de passe']
                ],
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Réinitialiser',
                'attr' => ['class' => 'btn btn-success']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FVUser::class,
        ]);
    }
}
