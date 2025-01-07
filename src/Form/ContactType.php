<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert as AssertAssert;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class,[
                'label' => 'Nom :',
                'constraints' =>[
                    new Assert\NotBlank([
                        'message' => 'Vous devez remplir tout les champs du formulaire',
                    ])
                ],
                'attr' => [
                    'placeholder' => 'ex: Daniel'
                ]
            ])
            ->add('firstname', TextType::class,[
                'label' => 'Prénom :',
                'constraints' =>[
                    new Assert\NotBlank([
                        'message' => 'Vous devez remplir tout les champs du formulaire',
                    ])
                ],
                'attr' =>[
                    'placeholder' => 'ex: Charles'
                ]
            ])
            ->add('email', EmailType::class,[
                'label' => 'Email :',
                'constraints' =>[
                    new Assert\NotBlank([
                        'message' => 'Vous devez remplir tout les champs du formulaire',
                    ])
                ],
                'attr' => [
                    'placeholder' => 'ex: c.daniel@exemple.com'
                ]
            ])
            ->add('subject', TextType::class,[
                'label' => 'Sujet :',
                'constraints' =>[
                    new Assert\NotBlank([
                        'message' => 'Vous devez remplir tout les champs du formulaire',
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Saisir le sujet du message'
                ]
            ])
            ->add('content', TextareaType::class,[
                'label' => 'Message :',
                'constraints' =>[
                    new Assert\NotBlank([
                        'message' => 'Vous devez remplir tout les champs du formulaire',
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Saisissez votre message...',
                    'class' => 'ct-textarea',
                    'rows' => 5,
                    'cols' => 50
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
