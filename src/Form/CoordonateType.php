<?php

namespace App\Form;

use App\Entity\Coordonate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class CoordonateType extends AbstractType
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
                'placeholder' => 'E-mail',
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide'
                ]),
            ],
        ])    
        ->add('address', TextType::class, [
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coordonate::class,
        ]);
    }
}
