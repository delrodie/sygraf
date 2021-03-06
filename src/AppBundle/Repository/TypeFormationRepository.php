<?php

namespace AppBundle\Repository;

/**
 * TypeFormationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TypeFormationRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Liste des formations
     */
    public function liste()
    {
        return $this->createQueryBuilder('tf')->where('tf.statut = 1');
    }

    /**
     * La formation selon le type
     * @uses by AffectationType
     */
    public function findBySlug($formation)
    {
        return $this->createQueryBuilder('tf')->where('tf.code = :formation')->setParameter('formation', $formation);
    }
}
