<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Group;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du groupe',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le nom du groupe est obligatoire.'
                    ])
                ]
            ])
            ->add('members', EntityType::class, [
                'label' => 'Sélectionner les contacts à ajouter dans le groupe',
                'class' => Contact::class,
                'choice_label' => function(Contact $contact) {
                    return $contact->getName() . ' ' . $contact->getFirstname() . ' ' . $contact->getPhoneNumber();
                },
                'multiple' => true,
                'expanded' => false,
                'by_reference' => false,
                'attr' => ['class' => 'form-control select2-multiple contact-select'],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                },
                'constraints' => [
                    new Assert\Count(['min' => 1, 'minMessage' => 'Vous devez sélectionner au moins un membre.'])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary mt-4'],
                'label' => $options['action_label']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
            'action_label' => 'Submit',
        ]);
    }


}
