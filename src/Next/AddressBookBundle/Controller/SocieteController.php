<?php

namespace Next\AddressBookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Next\AddressBookBundle\Entity\Societe;
use Next\AddressBookBundle\Form\SocieteType;

/**
 * Societe controller.
 *
 */
class SocieteController extends Controller
{

    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository('NextAddressBookBundle:Societe');
    }

    public function listAction()
    {
        $listeSocietes = $this->getRepository()->findAll();
        
        return $this->render('NextAddressBookBundle:Societe:list.html.twig', array(
            'societes' => $listeSocietes
        ));
    }

    public function addAction(Request $request)
    {
        $societe = new Societe();
        $form = $this->createForm(new SocieteType(), $societe);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($societe);
            $manager->flush();
            
            $this->addFlash('success', "{$societe->getNom()} a bien été ajouté");
            
            return $this->redirectToRoute('next_address_book_societe_list');
        }
        
        return $this->render('NextAddressBookBundle:Societe:add.html.twig', array(
            'form' => $form->createView()
        ));
        
    }
    
    public function showAction(Societe $societe)
    {
        return $this->render('NextAddressBookBundle:Societe:show.html.twig', array(
            'societe' => $societe
        ));
    }

    public function deleteAction(Societe $societe, Request $request)
    {
        if ($request->isMethod('post')) {
            if ($request->get('confirm') === 'Oui') {
                $manager = $this->getDoctrine()->getManager();
                $manager->remove($societe);
                $manager->flush();
            }
            
            $this->addFlash('success', "{$societe->getNom()} a bien été supprimé");
            
            return $this->redirectToRoute('next_address_book_societe_list');
        }
        
        return $this->render('NextAddressBookBundle:Societe:delete.html.twig', array(
            'societe' => $societe
        ));
    }

    public function modifyAction(Societe $societe, Request $request)
    {
        $form = $this->createForm(new SocieteType(), $societe);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($societe);
            $manager->flush();
            
            $this->addFlash('success', "{$societe->getNom()} a bien été modifié");
            
            return $this->redirectToRoute('next_address_book_societe_list');
        }
        
        return $this->render('NextAddressBookBundle:Societe:modify.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
