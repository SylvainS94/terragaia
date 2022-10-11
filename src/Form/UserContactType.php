<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                                    
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Titre du Message',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne peut être vide'
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 255,
                        'minMessage' => "Votre nom de catégorie est trop court. Le nombre de caractères minimal est {{ limit }}", 
                        'maxMessage' => "Votre nom de catégorie est trop long. Le nombre de caractères maximal est {{ limit }}",
                    ])
                 ],
                ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ici le contenu du Message'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne peut être vide'
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer un message',
                'attr' => [
                    'class' => 'd-block col-12 my-3 mx-auto btn btn-warning'
                ],
                'validate' => false,
                'label_html' => true,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
