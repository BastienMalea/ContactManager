<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
use Vich\UploaderBundle\Form\Type\VichImageType;


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
            ->add('groups', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'name', // le champ de l'entité Group à afficher dans les options
                'multiple' => true, // permet de sélectionner plusieurs groupes
                'expanded' => false, // false pour une liste déroulante, true pour des cases à cocher
                'label' => 'Groupe(s) du contact'// le label du champ
            ])
            ->add('customFields', CollectionType::class, [
                'entry_type' => CustomFieldType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Photo de profil',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
                'image_uri' => true
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary mt-4'],
                'label' => $options['action_label']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'action_label' => 'Submit',
            'allow_extra_fields' => true
        ]);
    }
}
