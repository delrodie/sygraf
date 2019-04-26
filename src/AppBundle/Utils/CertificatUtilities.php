<?php


namespace AppBundle\Utils;


use AppBundle\Entity\Certificat;
use Doctrine\ORM\EntityManager;

class CertificatUtilities
{
    function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Enregistrement des certificats
     */
    public function create($code1, $code2, $formation)
    {
        // Recupération du code de la formation
        $formation = $this->em->getRepository('AppBundle:TypeFormation')->findOneBy(['id'=>$formation]);

        // Generation des codes
        $i = $code1;
        while ($i<= $code2){
            if ($i < 10) $code = '000000'.$i.' '.$formation->getCode();
            elseif ($i < 100) $code = '00000'.$i.' '.$formation->getCode();
            elseif ($i < 1000) $code = '0000'.$i.' '.$formation->getCode();
            elseif ($i < 10000) $code = '000'.$i.' '.$formation->getCode();
            elseif ($i < 100000) $code = '00'.$i.' '.$formation->getCode();
            elseif ($i < 1000000) $code = '0'.$i.' '.$formation->getCode();
            else $code = '0'.$i.' '.$formation->getCode();

            // Verification de l'existence du code généré
            $certificatExist = $this->em->getRepository('AppBundle:Certificat')->findOneBy(['code'=>$code]);
            if ($certificatExist){
                return false;
            }
            $certificat = new Certificat();
            $certificat->setCode($code);
            $certificat->setFormation($formation);
            $certificat->setStatut(true);
            $certificat->setFlag(0);
            $this->em->persist($certificat);//dump($code2.'-'.$i);die();
            $this->em->flush();
            //$this->em->clear($this->certificat);

            $i++;
        }
        return true;
    }
}
