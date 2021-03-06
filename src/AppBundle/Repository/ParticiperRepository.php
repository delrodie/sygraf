<?php

namespace AppBundle\Repository;

use AppBundle\Controller\FormationController;
use AppBundle\Controller\ParticiperController;

/**
 * ParticiperRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ParticiperRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Liste des participants par ordre decroissant d'enregistrment
     * @uses ParticiperController::newAction()
     */
    public function findListDesc($formation)
    { //dump($formation);die();
        return $this->queryList($formation)->orderBy('p.id', 'DESC')->getQuery()->getResult();
    }

    /**
     * Liste des participants par ordre croissant des certificats
     * @uses FormationController::showAction()
     */
    public function findListAsc($formation)
    {
        return $this->queryList($formation)
                    ->orderBy('p.certificat', 'ASC')
                    ->getQuery()->getResult()
            ;
    }

    // requete de la liste des stagiaires
    public function queryList($formation)
    {
        return $this->createQueryBuilder('p')
                    ->innerJoin('p.formation', 'f')
                    ->where('f.slug = :formation')
                    ->setParameter('formation', $formation)
            ;

    }
}
