<?php

namespace AppBundle\Utils;


use Doctrine\ORM\EntityManager;

class GestionTitularisation
{
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Mise a jour de buchette dans la table chef
     * use TitularisationController::New
     */
    public function majBuchette($chef,$buchette)
    {
        $titulaire = $this->em->getRepository("AppBundle:Chef")->findOneBy(['id'=>$chef->getId()]);
        //dump($titulaire);die();
        if ($titulaire){
            $titulaire->setBuchette($buchette);
            $this->em->flush();

            return true;
        }else {
            return false;
        }
    }

}