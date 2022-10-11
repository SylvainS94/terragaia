<?php

namespace App\Form;

use App\Entity\Privacy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class PrivacyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('phone', TelType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Numéro de téléphone',
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide'
                ]),
            ],
        ])
        ->add('email', EmailType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Email',
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide'
                ]),
            ],
        ])
        ->add('adress', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Adresse postale',
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide'
                ]),
                new Length([
                    'min' => 3,
                    'max' => 255,
                    'minMessage' => "Votre adresse est trop courte. Le nombre de caractères minimal est {{ limit }}", 
                    'maxMessage' => "Votre adresse est trop longue. Le nombre de caractères maximal est {{ limit }}",
                ])
             ],
        ])
        ->add('firstname', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Prénom',
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide'
                ]),
                new Length([
                    'min' => 2,
                    'max' => 255,
                    'minMessage' => "Votre prénom est trop court. Le nombre de caractères minimal est {{ limit }}", 
                    'maxMessage' => "Votre prénom est trop long. Le nombre de caractères maximal est {{ limit }}",
                ])
            ],
        ])
        ->add('lastname', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Nom',
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide'
                ]),
                new Length([
                    'min' => 2,
                    'max' => 255,
                    'minMessage' => "Votre nom est trop court. Le nombre de caractères minimal est {{ limit }}", 
                    'maxMessage' => "Votre nom est trop long. Le nombre de caractères maximal est {{ limit }}",
                ])
            ],
        ])
        ->add('access', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Données Accès et Hébergement'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => "Ce champ ne peut être vide"
                ]),
            ],
        ])
        ->add('process', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Traitement de données'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => "Ce champ ne peut être vide"
                ]),
            ],
        ])
        ->add('cookie', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Cookies et autres technologies'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => "Ce champ ne peut être vide"
                ]),
            ],
        ])
        ->add('analytic', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Utilisation cookie publicité et analyse'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => "Ce champ ne peut être vide"
                ]),
            ],
        ])
        ->add('network', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Réseaux sociaux'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => "Ce champ ne peut être vide"
                ]),
            ],
        ])
        ->add('law', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Droits Utilisateur'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => "Ce champ ne peut être vide"
                ]),
            ],
        ])
        ->add('possibility', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Opposition Utilisateur'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => "Ce champ ne peut être vide"
                ]),
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Privacy::class,
        ]);
    }
}
