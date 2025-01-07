<?php

namespace App\Form;

use App\Entity\FVUser;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class PasswordFVUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword', PasswordType::class, [
                'label' => 'Votre mot de passe actuel :',
                'attr' => ['placeholder' => 'Entrez votre mot de passe actuel'],
                'mapped' => false
            ])
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
                'label' => 'Mettre a jour',
                'attr' => ['class' => 'btn btn-success']
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event){
                $form = $event->getForm();
                $user = $form->getConfig()->getOptions()['data'];
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];

                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get('actualPassword')->getData()
                );
                
                if(!$isValid){
                    $form->get('actualPassword')->addError(new FormError('Le mot de passe actuel n\'est pas conforme au mot de passe enregistré'));
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FVUser::class,
            'passwordHasher' => null
        ]);
    }
}
