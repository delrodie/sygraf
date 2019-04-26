<?php


namespace AppBundle\Utils;


use Doctrine\ORM\EntityManager;

class Gestiondistrict
{
    function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function duplicate($libelle)
    {
        $district = $this->em->getRepository('AppBundle:District')->findOneBy(['libelle'=>$libelle]);

        //Si le district existe alors retourner de vrai
        if ($district) return true;
        else return false;
    }
}
