<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'lastname',
                'label' => 'Choisissez un Utilisateur'
            ])
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
                        'minMessage' => "Le titre de votre message est trop court. Le nombre de caractères minimal est {{ limit }}", 
                        'maxMessage' => "Le titre de votre message est trop long. Le nombre de caractères maximal est {{ limit }}",
                    ])
                 ],
                ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ici le contenu du Message'
                ],
            ])

            //->add('created_At', DateTimeType::class)
            // ->add('updated_At', DateTimeType::class)  Ajouter setters dans controller
            // ->add('deleted_At', DateTimeType::class)  Ajouter setters dans controller
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
