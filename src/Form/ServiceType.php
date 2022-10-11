<?php

namespace App\Form;

use App\Entity\Service;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('category', EntityType::class, [
                 'class' => Category::class,
                'choice_label' => 'title',
                'label' => 'Choisissez une catégorie de Clients'
        ])
            ->add('title', TextType::class, [
                'label' => 'Titre du Coaching/Accompagnement',
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne peut être vide"
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 255,
                        'minMessage' => "Votre titre est trop court. Le nombre de caractères minimal est {{ limit }}", 
                        'maxMessage' => "Votre titre est trop long. Le nombre de caractères maximal est {{ limit }}",
                    ])
                ],
            ])
            ->add('subtitle', TextType::class, [
                'label' => 'Sous-titre',
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne peut être vide"
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 255,
                        'minMessage' => "Votre titre est trop court. Le nombre de caractères minimal est {{ limit }}", 
                        'maxMessage' => "Votre titre est trop long. Le nombre de caractères maximal est {{ limit }}",
                    ])
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu', // false (test 28/04)
                'attr' => [
                    'placeholder' => 'Ici le contenu du service'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne peut être vide"
                    ]),
                ],
            ])
            ->add('picture', FileType::class, [
                'label' => 'Photo d\'illustration',
                'data_class' => null, // Permet de paramétrer le type de classe de données a null (par defaut data_class = File)
                'attr' => [
                    'data-default-file' => $options['picture'] // $options dans cette fonction buildForm et clé 'photo' de admin controller
                ],
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => "Les types de photo autorisés sont : .jpg et .png",
                    ]),
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne peut être vide"
                    ]),
                ],
            ])
            ->add('progress', TextType::class, [
                'label' => 'Déroulement',
                'attr' => [
                    'placeholder' => 'Déroulement des étapes'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne peut être vide"
                    ]),
                ],
            ])
            ->add('target', TextType::class, [
                'label' => 'Personnes accompagnées',
                'attr' => [
                    'placeholder' => 'Cible des personnes accompagnées'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne peut être vide"
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 255,
                        'minMessage' => "Votre texte est trop court. Le nombre de caractères minimal est {{ limit }}", 
                        'maxMessage' => "Votre texte est trop long. Le nombre de caractères maximal est {{ limit }}",
                    ])
                ],
            ])
            ->add('rythm', TextType::class, [
                'label' => 'Répartition des Séances',
                'attr' => [
                    'placeholder' => 'Répartition des Séances'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne peut être vide"
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 255,
                        'minMessage' => "Votre texte est trop court. Le nombre de caractères minimal est {{ limit }}", 
                        'maxMessage' => "Votre texte est trop long. Le nombre de caractères maximal est {{ limit }}",
                    ])
                ],
            ])
            ->add('sessionmin', NumberType::class, [
                'label' => 'Séances minimum',
                'attr' => [
                    'placeholder' => 'Nombre de séances minimum (en Chiffre)'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ est un chiffre et ne peut être vide"
                    ]),
                ],
            ])
            ->add('sessionmax', NumberType::class, [
                'label' => 'Séances maximum',
                'attr' => [
                    'placeholder' => 'Nombre de séances maximum (en Chiffre)'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ est un chiffre et ne peut être vide"
                    ]),
                ],
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Durée séance',
                'attr' => [
                    'placeholder' => 'Durée 1 séance (en Chiffre)'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ est un chiffre ne peut être vide"
                    ]),
                ],
            ])
            ->add('price', TextType::class, [
                'label' => 'Tarification Séances',
                'attr' => [
                    'placeholder' => 'Tarification Séances'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne peut être vide"
                    ]),
                ],
            ])
            ->add('modality', TextType::class, [
                'label' => 'Modalités financières',
                'attr' => [
                    'placeholder' => 'Modalités financières'
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
            'data_class' => Service::class,
            'picture' => null,
        ]);
    }
}
