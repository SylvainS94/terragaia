<?php

namespace App\Form;

use App\Entity\Theme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ThemeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du Thème',
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
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ici le contenu du Thème'
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
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Theme::class,
            'picture' => null,
        ]);
    }
}
