<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Choice;
use App\Entity\Booking;
use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BookingAdminFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'lastname',
                'label' => 'Choisissez un membre'
            ])
            ->add('message', TextType::class, [
                'label' => 'Message',
            ])
            ->add('choices', EntityType::class, [
                'class' => Choice::class,
               'choice_label' => 'title',
               'label' => 'Choisissez un type de RDV'
            ])
            ->add('services', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'title',
                'label' => 'Choisissez un type de Coaching/Accompagnement'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
