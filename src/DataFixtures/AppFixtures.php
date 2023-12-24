<?php

namespace App\DataFixtures;

use App\Entity\Contact;
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
        for($i = 0; $i < 50; $i++){
            $contact = new Contact();
            $contact ->setName($this->faker->word())
                ->setFirstname($this->faker->word())
                ->setPhoneNumber($this->faker->regexify('[0-9]{10}'))
                ->setEmail($this->faker->email());
            $manager->persist($contact);
        }

        $manager->flush();
    }
}
