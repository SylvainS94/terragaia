<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Service;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Votre email*',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide'
                ]),
                new Length([
                    'min' => 3,
                    'max' => 180,
                    'minMessage' => "Votre email est trop court. Le nombre de caractères minimal est {{ limit }}", 
                    'maxMessage' => "Votre email est trop long. Le nombre de caractères maximal est {{ limit }}",
                ])
            ],
        ])
    ;
        // Si c'est un update_user, alors on ne rend pas l'input de password. Ce champs est donc réservé à l'inscription / ! = (null === false)
        if( null=== $this->security->getUser()) {
           
            $builder
                ->add('password', PasswordType::class, [
                    'label' => 'Indiquez votre mot de passe',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Ce champs ne peut être vide'
                        ]),
                        new Length([
                            'min' => 3,
                            'max' => 255,
                            'minMessage' => "Votre mot de passe est trop court. Le nombre de caractères minimal est {{ limit }}", 
                            'maxMessage' => "Votre mot de passe est trop long. Le nombre de caractères maximal est {{ limit }}",
                        ])
                    ],
                ])
            ;
        }

        $builder
        ->add('firstname', TextType::class, [
            'label' => 'Votre Prénom*',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide'
                ]),
                new Length([
                    'min' => 2,
                    'max' => 255,
                    'minMessage' => "Votre prénom est trop court. Le nombre de caractères minimal est {{ limit }}", 
                    'maxMessage' => "Votre prénom est trop long. Le nombre de caractères maximal est {{ limit }}",
                ])
            ],
        ])
        ->add('lastname', TextType::class, [
            'label' => 'Votre Nom*',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide'
                ]),
                new Length([
                    'min' => 2,
                    'max' => 255,
                    'minMessage' => "Votre nom est trop court. Le nombre de caractères minimal est {{ limit }}", 
                    'maxMessage' => "Votre nom est trop long. Le nombre de caractères maximal est {{ limit }}",
                ])
            ],
        ])
        ->add('phone', TelType::class, [
            'label' => 'Votre numéro de Téléphone*',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide'
                ]),
                new Length([
                    'min' => 10,
                    'max' => 100,
                    'minMessage' => "Votre numéro de téléphone est trop court. Le nombre de caractères minimal est {{ limit }}", 
                    'maxMessage' => "Votre numéro de téléphone est trop long. Le nombre de caractères maximal est {{ limit }}",
                ])
            ],
        ])
        ->add('society', TextType::class, [
            'label' => 'Nom de votre entreprise, association, école... (Sinon laisser vide)',
            'constraints' => [
                new Length([
                    'min' => 2,
                    'max' => 50,
                    'minMessage' => "Votre nom d'entreprise, association, école est trop court. Le nombre de caractères minimal est {{ limit }}", 
                    'maxMessage' => "Votre nom de d'entreprise, association, école est trop long. Le nombre de caractères maximal est {{ limit }}",
                ])
            ],
        ])
        ->add('job', TextType::class, [
            'label' => 'Votre profession, cursus étudiant... (Sinon laisser vide)',
            'constraints' => [
                new Length([
                    'min' => 2,
                    'max' => 50,
                    'minMessage' => "Votre nom de profession ou cursus étudiant est trop court. Le nombre de caractères minimal est {{ limit }}", 
                    'maxMessage' => "Votre nom de profession ou cursus étudiant est trop long. Le nombre de caractères maximal est {{ limit }}",
                ])
            ], 
        ])
        ->add('ask_object', TextType::class, [
            'label' => 'Objet de votre demande*',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide'
                ]),
                new Length([
                    'min' => 3,
                    'max' => 255,
                    'minMessage' => "L'objet de votre demande est trop court. Le nombre de caractères minimal est {{ limit }}", 
                    'maxMessage' => "L'objet de votre demande est trop long. Le nombre de caractères maximal est {{ limit }}",
                ])
            ],
        ])
        
        // ->add('category', EntityType::class, [
        //     'class' => Category::class,
        //     'choice_label' => 'title',
        //     'label' => 'Choisissez une catégorie',
        // ])
        // ->add('service', EntityType::class, [
        //     'class' => Service::class,
        //     'choice_label' => 'title',
        //     'label' => 'Indiquez un Coaching/Accompagnement',
        // ])
        ->add('agreeTerms', CheckboxType::class, [
            'label' => 'Accepter nos conditions RGPD* **',
            'mapped' => false,
            'constraints' => [
                new IsTrue([
                    'message' => 'Vous devez accepter nos conditions',
                ]),
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => null === $this->security->getUser() ? "Je m'inscris" : "J'actualise mon compte", // ? = if = pas de user enregistré alors je m'inscris et : = else dans le cas ou il trouve un user par le managerInterface alors on update
            'validate' => false,
            'attr' => [
                'class' => 'd-block col-5 my-3 mx-auto btn btn-warning'
            ]
        ])      
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}


