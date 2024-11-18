<?php

namespace App\Tests\Unit\Entity;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\DataFixtures\ContactFixtures;
use App\Entity\Contact;

class ContactTest extends WebTestCase
{
    /** @var AbstractDatabaseTool */
    protected $databaseTool;

    public function setUp(): void
    {
        parent::setUp();

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        
    }

    public function testIfLoadedContactExists(): void
    {

        $this->databaseTool->loadFixtures([
            'App\DataFixtures\ContactFixtures'
        ]);

        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        
        $contactRepository = $entityManager->getRepository(Contact::class);

        $contact = $contactRepository->findOneBy(['name' => 'Manuel']);
        $this->assertNotNull($contact, 'Contact with name Manuel should exist.');
        $this->assertEquals('Manuel', $contact->getName(), 'The contact name should be "Manuel".');

    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}