<?php

namespace App\Form;

use App\Entity\Choice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;

class ChoiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Titre du Type de RDV',
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide'
                ]),
                new Length([
                    'min' => 3,
                    'max' => 255,
                    'minMessage' => "Le titre du Type de RDV est trop court. Le nombre de caractères minimal est {{ limit }}", 
                    'maxMessage' => "Le titre du Type de RDV est trop long. Le nombre de caractères maximal est {{ limit }}",
                ])
             ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Choice::class,
        ]);
    }
}
