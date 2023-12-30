<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\CustomField;
use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        // Création des contacts
        $contacts = [];
        for ($i = 0; $i < 50; $i++) {
            $contact = new Contact();
            $contact->setName($this->faker->lastName())
                ->setFirstname($this->faker->firstName())
                ->setPhoneNumber($this->faker->regexify('[0-9]{10}'))
                ->setEmail($this->faker->email());
            $manager->persist($contact);
            $contacts[] = $contact;

            // Ajout de champs personnalisés pour les 20 premiers contacts
            if ($i < 20) {
                $customField1 = new CustomField();
                $customField1->setName('adresse');
                $customField1->setValue($this->faker->address());
                $contact->addCustomField($customField1);

                $customField2 = new CustomField();
                $customField2->setName('taille');
                $customField2->setValue($this->faker->numberBetween(150, 200) . ' cm');
                $contact->addCustomField($customField2);

                $customField3 = new CustomField();
                $customField3->setName('poids');
                $customField3->setValue($this->faker->numberBetween(40, 120) . ' kg');
                $contact->addCustomField($customField3);

                $manager->persist($customField1);
                $manager->persist($customField2);
                $manager->persist($customField3);
            }
        }

        // Création des groupes et assignement des contacts aléatoirement
        for ($j = 0; $j < 10; $j++) {
            $group = new Group();
            $group->setName($this->faker->word());

            // Déterminer un nombre aléatoire de contacts à ajouter au group
            $numContacts = mt_rand(1, count($contacts));
            for ($k = 0; $k < $numContacts; $k++) {
                // Sélectionner un contact aléatoire et l'ajouter au group
                $randomContact = $contacts[mt_rand(0, count($contacts) - 1)];
                $group->addMember($randomContact);
            }

            $manager->persist($group);
        }

        $manager->flush();
    }
}
