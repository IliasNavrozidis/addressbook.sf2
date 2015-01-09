<?php

namespace Next\AddressBookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of ContactType
 *
 * @author stagiaire
 */
class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("prenom", "text")
                ->add("nom", "text")
                ->add("email", "email", array("required" => false))
                ->add("telephone", "text", array("required" => false))
                ->add("societe", "entity", array(
                    "class" => "NextAddressBookBundle:Societe",
                    "property" => "nom",
                    "required" => false
                ))
                ->add("groupes", "entity", array(
                    "multiple" => true,
                    "class" => "NextAddressBookBundle:Groupe",
                    "property" => "nom",
                    "required" => false
                ))
                ->add("save", "submit");
//                ->add("save", "submit", array("attr" => array("class" => "btn btn-primary")));
    }

    
    public function getName()
    {
        return 'contact';
    }
}
