<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Commentary;
use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentaryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('service', EntityType::class, [
            'class' => Service::class,
           'choice_label' => 'title',
           'label' => 'Choisissez un Coaching/Accompagnement'
   ])
        ->add('author', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'lastname',
            'label' => 'Choisissez un Utilisateur'
        ])
        ->add('comment', TextareaType::class, [
            'label' => 'Commentez ici',
            'attr' => [
                'placeholder' => 'Commentez ici ce Coaching/Accompagnement'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Le commentaire ne peut être vide'
                ])
            ],
        ])
            // ->add('created_At')
            // ->add('updated_At')
            // ->add('deleted_At')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentary::class,
        ]);
    }
}
