<?php

namespace AppBundle\Repository;

use AppBundle\Controller\FormationController;

/**
 * FormationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FormationRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Liste decroissante des formation
     * @uses FormationController:newAction, FormationController:EditAction
     */
    public function findDesc($region = null)
    {
        if ($region){
            return $this->createQueryBuilder('f')
                        ->where('f.region = :region')
                        ->orderBy('f.id', 'DESC')
                        ->getQuery()->getResult()
                ;
        }else{
            return $this->createQueryBuilder('f')
                        ->orderBy('f.id', 'DESC')
                        ->getQuery()->getResult()
                ;
        }
    }
}
