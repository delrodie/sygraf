<?php


namespace AppBundle\Utils;


use Doctrine\ORM\EntityManager;

class RegionUtilities
{
    function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    // Verification de l'existence de la region
    public function duplicate($libelle)
    {
        // Recupération de la region
        // si la region existe alors "Return true" sinon "Return false"
        $region = $this->em->getRepository('AppBundle:Region')->findOneBy(['libelle'=>$libelle]);
        if ($region) return true;
        else return false;

    }
}
