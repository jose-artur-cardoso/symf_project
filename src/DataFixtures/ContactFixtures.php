<?php
// src/DataFixtures/ContactFixtures.php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use DateTime;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $contact1 = new Contact();
        $contact1->setName('Manuel')
            ->setEmail('manuel@example.com')
            ->setPhones(['1234567890'])
            ->setBirthday(new DateTime('1990-09-01'));

        $contact2 = new Contact();
        $contact2->setName('João')
            ->setEmail('joao@example.com')
            ->setPhones(['0987654321'])
            ->setBirthday(new DateTime('1990-12-12'));

        $manager->persist($contact1);
        $manager->persist($contact2);

        $manager->flush();
    }
}
