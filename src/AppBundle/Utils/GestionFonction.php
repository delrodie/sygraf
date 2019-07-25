<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Fonction;
use Doctrine\ORM\EntityManager;

class GestionFonction
{
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Enregistrement de la fonction du chef
     * use TitularisationController::new
     */
    public function create($chef,$libelle,$entite,$date)
    {
        $fonction = new Fonction(); //dump($chef);die();
        $fonction->setChef($chef);
        $fonction->setLibelle($libelle);
        $fonction->setEntite($entite);
        $fonction->setDateFonction(substr($date,0,4));
        $this->em->persist($fonction);
        $this->em->flush();

        return true;

    }

    /**
     * Modification de la fonction du chef
     * use TitularisationController::Edit
     */
    public function update($chef,$libelle,$entite,$date,$reference)
    {
        $fonction = $this->em->getRepository("AppBundle:Fonction")->findOneBy(['reference'=>$reference]); //dump($fonction);die();
        if (!$fonction) return false;
        $fonction->setChef($chef);
        $fonction->setLibelle($libelle);
        $fonction->setEntite($entite);
        $fonction->setDateFonction(substr($date,0,4));
        $this->em->flush();

        return true;
    }

    /**
     * Mise a jour du statut
     * flag 1 <=> Formation / flag 2 <=> Titularisation
     * reference <=> id de l'entité concernée
     * use TitularisationCOntroller::new
     */
    public function maj($chef,$flag,$reference)
    {
        $fonction = $this->em->getRepository("AppBundle:Fonction")->findOneBy(['chef'=>$chef], ['id'=>'DESC']);
        $fonction->setFlag($flag);
        $fonction->setReference($reference);
        $this->em->flush();

        return true;

    }

    /**
     * Suppression de la fonction
     * use TitularisationController::delete
     */
    public function delete($reference)
    {
        $fonction = $this->em->getRepository("AppBundle:Fonction")->findOneBy(['reference'=>$reference]);
        $this->em->remove($fonction);
        $this->em->flush();

        return true;
    }
}