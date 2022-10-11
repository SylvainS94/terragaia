<?php

namespace App\Form;

use App\Services\SearchCalendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateAppointment', DateType::class, [
                'label' => 'Entrez une date ou cliquez sur l\'icône calendrier ci dessous pour voir mes disponibilités :',
                'widget' => "single_text"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => SearchCalendar::class,
        ]);
    }
}
