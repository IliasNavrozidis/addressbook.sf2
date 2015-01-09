<?php

namespace Next\AddressBookBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    protected function setUp()
    {
        // TODO réinitialiser la base avec des données
    }
    
    public function testListAvecBaseDeDonnees()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contact/');
        
        $this->assertEquals($client->getResponse()->getStatusCode(), 200);
        $this->assertEquals($crawler->filter("h1")->html(), 'Liste des contacts');
        // TODO charger des fixtures
        $this->assertEquals($crawler->filter("table tr")->count(), 101);
    }
    
    public function testListSansBaseDeDonneesSansContacts()
    {
        $client = static::createClient();

        
        
        // Premièrement, mockez l'objet qui va être utilisé dans le test
//        $contact = $this->getMock('\Next\AddressBookBundle\Entity\Contact');
//        $contact->expects($this->any())
//            ->method('getSalary')
//            ->will($this->returnValue(1000));
//        $contact->expects($this->once())
//            ->method('getBonus')
//            ->will($this->returnValue(1100));

        // Maintenant, mockez le repository pour qu'il retourne un mock de l'objet emloyee
        $contactRepository = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $contactRepository->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue(array()));
        
        $client->getContainer()->set('next.address_book_bundle.contact.repository', $contactRepository);

        $crawler = $client->request('GET', '/contact/');
        
        $this->assertEquals($client->getResponse()->getStatusCode(), 200);
        $this->assertEquals($crawler->filter("h1")->html(), 'Liste des contacts');
        $this->assertEquals($crawler->filter("table tr")->count(), 1);
    }
    
    public function testListSansBaseDeDonneesAvecContacts()
    {
        $client = static::createClient();

        // Premièrement, mockez l'objet qui va être utilisé dans le test
        $contact = $this->getMockBuilder('\Next\AddressBookBundle\Entity\Contact')
                        ->getMock();
        
        $contact->expects($this->exactly(3))
                ->method('getId')
                ->willReturn(1);
        
        $contact->expects($this->once())
                ->method('getPrenom')
                ->willReturn('Romain');
        
        $contact->expects($this->once())
                ->method('getNom')
                ->willReturn('Bohdanowicz');
          
        // Maintenant, mockez le repository pour qu'il retourne un mock de l'objet emloyee
        $contactRepository = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $contactRepository->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue(array($contact)));
        
        $client->getContainer()->set('next.address_book_bundle.contact.repository', $contactRepository);

        $crawler = $client->request('GET', '/contact/');
        
        $this->assertEquals($client->getResponse()->getStatusCode(), 200);
        $this->assertEquals($crawler->filter("h1")->html(), 'Liste des contacts');
        $this->assertEquals($crawler->filter("table tr")->count(), 2);
        $this->assertEquals($crawler->filter("table tr:nth-child(2) td:first-child")->html(), 'Romain Bohdanowicz');
    }

    public function testAdd()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/add');
    }

    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/{id}');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/2/delete');
    }

    public function testModify()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/{id}/modify');
    }

}
