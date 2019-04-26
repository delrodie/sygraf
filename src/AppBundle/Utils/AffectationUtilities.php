<?php


namespace AppBundle\Utils;


use Doctrine\ORM\EntityManager;

class AffectationUtilities
{
    function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

}
