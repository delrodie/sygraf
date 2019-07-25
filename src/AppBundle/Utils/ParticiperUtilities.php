<?php


namespace AppBundle\Utils;


use AppBundle\Entity\Participer;
use Doctrine\ORM\EntityManager;
use AppBundle\Utils\GestionFonction;

class ParticiperUtilities
{
    function __construct(EntityManager $entityManager, GestionFonction $gestionFonction)
    {
        $this->em = $entityManager;
        $this->gestionFonction = $gestionFonction;
    }

    public function create($participer)
    {
        $annee = substr($participer->getFormation()->getDebut(),6,4);
        $participant = new Participer();
        $participant->setAnnee($annee);
        $participant->setChef($participer->getChef());
        $participant->setCertificat($participer->getCertificat());
        $participant->setFormation($participer->getFormation());
        //Changement de statut du certificat
        $certificat = $participer->getCertificat();
        $certificat->setFlag(2);
        //Changement de niveau du chef
        $level = substr($certificat->getCode(),8,1);
        $chef = $participer->getChef();
        $chef->setClasse($level);
        // Activation de la titularisation
        if ($level == 'A'){ $chef->setTitularisation(true);}
        // Ajout d'un stagiaire de plus au nombre de stagiaires concernés par la formation
        $formation = $participer->getFormation();
        $nombre = $formation->getStagiaire() + 1;
        $formation->setStagiaire($nombre) ;
        //dump($participant);die();

        $this->em->persist($participant);
        $this->em->flush();
        $this->em->persist($certificat);
        $this->em->persist($chef);
        $this->em->persist($formation);
        $this->em->flush(); //dump();die();
        $this->gestionFonction->create($participant->getChef(), $participer->getFonction(), $participer->getEntite(),$annee);
        $this->gestionFonction->maj($participant->getChef()->getId(),1,$participant->getId());

        return true;
    }
}
