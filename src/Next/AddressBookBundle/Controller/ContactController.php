<?php

namespace Next\AddressBookBundle\Controller;

use Next\AddressBookBundle\Entity\Contact;
use Next\AddressBookBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ContactController extends Controller
{
    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository('NextAddressBookBundle:Contact');
    }

    public function listAction()
    {
        /* @var $repo \Doctrine\ORM\EntityRepository */
        $repo = $this->get('next.address_book_bundle.contact.repository');
        $listeContacts = $repo->findBy(array(), null, 100);
        
        return $this->render('NextAddressBookBundle:Contact:list.html.twig', array(
            'contacts' => $listeContacts
        ));
    }

    public function addAction(Request $request)
    {
        $contact = $this->get('next.address_book_bundle.contact.entity');
        $form = $this->createForm(new ContactType(), $contact);
        
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');
            $manager->persist($contact);
            $manager->flush();
            
            $this->addFlash('success', "{$contact->getPrenom()} {$contact->getNom()} a bien été ajouté");
            
            return $this->redirectToRoute('next_address_book_contact_list');
        }
        
        return $this->render('NextAddressBookBundle:Contact:add.html.twig', array(
            'form' => $form->createView()
        ));
        
    }
    
    /**
    * @ParamConverter("contact", class="NextAddressBookBundle:Contact", options={"repository_method" = "findWithSociete"})
    */
    public function showAction(Contact $contact)
    {
        return $this->render('NextAddressBookBundle:Contact:show.html.twig', array(
            'contact' => $contact
        ));
    }

    public function deleteAction(Contact $contact, Request $request)
    {
        if ($request->isMethod('post')) {
            if ($request->get('confirm') === 'Oui') {
                $manager = $this->getDoctrine()->getManager();
                $manager->remove($contact);
                $manager->flush();
            }
            
            $this->addFlash('success', "{$contact->getPrenom()} {$contact->getNom()} a bien été supprimé");
            
            return $this->redirectToRoute('next_address_book_contact_list');
        }
        
        return $this->render('NextAddressBookBundle:Contact:delete.html.twig', array(
            'contact' => $contact
        ));
    }

    public function modifyAction(Contact $contact, Request $request)
    {
        $form = $this->createForm(new ContactType(), $contact);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($contact);
            $manager->flush();
            
            $this->addFlash('success', "{$contact->getPrenom()} {$contact->getNom()} a bien été modifié");
            
            return $this->redirectToRoute('next_address_book_contact_list');
        }
        
        return $this->render('NextAddressBookBundle:Contact:modify.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
