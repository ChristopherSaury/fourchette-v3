<?php

namespace App\Form;

use App\Entity\FVUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegisterFVUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email :',
                'attr' => ['class' => 'formInput','placeholder' => 'ex: tdaniel@exemple.com'],
                'mapped' => true
            ])
            ->add('firstname', TextType::class,[
                'label' => 'Prénom :',
                'attr' => ['class' => 'formInput','placeholder' => 'ex: Tony']
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Nom :',
                'attr' => ['class' => 'formInput','placeholder' => 'ex: Daniel']
            ])
            ->add('plainPassword', RepeatedType::class,[
                'type' => PasswordType::class,
                'label' => false,
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
                'first_options' => ['label' => 'Mot de passe :','attr' => ['class' => 'formInput','placeholder' =>  'Saisissez un mot de passe :'], 'hash_property_path' => 'password'],
                'second_options' => ['label' => 'Confirmez mot de passe :','attr' => [ 'class' => 'formInput', 'placeholder' =>  'Confirmez votre mot de passe :']],
                'mapped' => false
            ])
            ->add('agreeTerms', CheckboxType::class,[
                'label' => 'J\'ai lu et j\'accepte les <a href="/conditions-generales-utilisation" target="_blank">conditions d\'utilisation</a>',
                'label_html' => true,
                'mapped' => true,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions d’utilisation pour continuer.',
                    ])
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Terminer',
                'attr' => ['class' => 'btn signUpBtn']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' =>[
                new UniqueEntity([
                    'entityClass' => FVUser::class,
                    'fields' => 'email',
                    'message' => 'Cette adresse email est déjà utilisée'
                ])
            ],
            'data_class' => FVUser::class,
        ]);
    }
}
