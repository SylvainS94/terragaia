<?php

namespace App\Form;

use App\Entity\Disponibility;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisponibilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_slot',DateType::class, [
                'label' => 'Indiquez le jour',
                'widget'=> 'single_text',
                'mapped'=> false,
            ])
            ->add('hour_slot', TimeType::class, [
                'label' => 'Indiquez une heure de début du RDV/Séance',
                'widget'=> 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Disponibility::class,
        ]);
    }
}
