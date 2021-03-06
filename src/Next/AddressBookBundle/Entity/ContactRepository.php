<?php

namespace Next\AddressBookBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ContactRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContactRepository extends EntityRepository
{
    public function findWithSociete($id) {
        $dql = "SELECT c, s "
             . "FROM NextAddressBookBundle:Contact c "
             . "LEFT JOIN c.societe s "
             . "WHERE c.id = :id";
        
        $query = $this->_em->createQuery($dql);
        $query->setParameter('id', $id);
        
        return $query->getSingleResult();
    }
}
