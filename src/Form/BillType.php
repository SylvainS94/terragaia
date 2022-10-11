<?php

namespace App\Form;

use App\Entity\Bill;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class BillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'lastname',
                'label' => 'Choisissez un membre'
            ])

            ->add('content', FileType::class, [
                'label' => 'Fichier .pdf',
                'data_class' => null, // Permet de paramétrer le type de classe de données a null (par defaut data_class = File)
                'attr' => [
                    'data-default-file' => $options['content'] // $options dans cette fonction buildForm et clé 'content' de admin controller
                ],
                'constraints' => [
                    new File([
                        'mimeTypes' => ['file/pdf'],
                        'mimeTypesMessage' => "Le type de fichier autorisé est : .pdf",
                    ]),
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne peut être vide"
                    ]),
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
            'data_class' => Bill::class,
            'content' => null,
        ]);
    }
}
