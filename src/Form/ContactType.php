<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;


class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', 'minlength' => 2, 'maxlength' => 50],
                'label' => 'Nom',
                'label_attr' => ['class' => 'form-label mt-4'],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('firstname', TextType::class, [
                'attr' => ['class' => 'form-control', 'minlength' => 2, 'maxlength' => 50],
                'label' => 'Prénom',
                'label_attr' => ['class' => 'form-label mt-4'],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('phoneNumber', TextType::class, [
                'attr' => ['class' => 'form-control', 'minlength' => 10, 'maxlength' => 10],
                'label' => 'Numéro de téléphone',
                'label_attr' => ['class' => 'form-label mt-4'],
                'constraints' => [
                    new Assert\Length(['min' => 10, 'max' => 10]),
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^\d{10}$/',
                        'message' => 'Le numéro de téléphone doit être composé de 10 chiffres.'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Email',
                'required' => false,
                'label_attr' => ['class' => 'form-label mt-4'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer une adresse email.',
                        'allowNull' => true
                    ]),
                    new Assert\Email(['message' => 'L\'email "{{ value }}" n\'est pas un email valide.'])
                ]
            ])
            ->add('customFields', CollectionType::class, [
                'entry_type' => CustomFieldType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ])
            ->add('imageName', FileType::class, [
                'label' => 'Photo de profil (fichier JPG/PNG/PDF)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'application/pdf',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un document valide (JPG/PNG/PDF).',
                    ])
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary mt-4'],
                'label' => $options['action_label']
            ]);





//            ->add('memberGroups', EntityType::class, [
//                'class' => Group::class,
//'choice_label' => 'id',
//'multiple' => true,
//            ])
//        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'action_label' => 'Submit'
        ]);
    }
}
