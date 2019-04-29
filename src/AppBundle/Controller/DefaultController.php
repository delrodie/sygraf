<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // Nombre des certificats en stock
        $cep = $em->getRepository('AppBundle:Certificat')->findNombre('C',0);
        $prebadge = $em->getRepository('AppBundle:Certificat')->findNombre('B',0);
        $badge = $em->getRepository('AppBundle:Certificat')->findNombre('A',0);

        // Nombre de chefs
        $nombre_chef = $em->getRepository('AppBundle:Chef')->findNombreNational();
        $chef_femme = $em->getRepository('AppBundle:Chef')->findNombreNational('F');
        $chef_homme = $em->getRepository('AppBundle:Chef')->findNombreNational('M');
        $homme = round(($chef_homme*100)/$nombre_chef, 0);
        $femme = round(($chef_femme*100)/$nombre_chef);

        // Pourcentage de chef selon le tyoe de formation
        $chef_cep = $em->getRepository('AppBundle:Chef')->findNombreByClasse('C');
        $chef_prebadge = $em->getRepository('AppBundle:Chef')->findNombreByClasse('B');
        $chef_badge = $em->getRepository('AppBundle:Chef')->findNombreByClasse('A');
        $pourcentageCEP = round(($chef_cep*100)/$nombre_chef, 1);
        $pourcentageBadge = round(($chef_badge*100)/$nombre_chef, 1);
        $pourcentagePrebadge = round(($chef_prebadge*100)/$nombre_chef, 1);

        // Liste des regions
        $regions = $em->getRepository('AppBundle:Region')->liste()->getQuery()->getResult();

        return $this->render('default/index.html.twig', [
            'current_page' => 'accueil',
            'cep' => $cep,
            'prebadge' => $prebadge,
            'badge' => $badge,
            'nombreChef' => $nombre_chef,
            'nombreFemme' => $femme,
            'nombreHomme'=> $homme,
            'nombreCEP' => $chef_cep,
            'nombrePrebadge'=> $chef_prebadge,
            'nombreBadge'=> $chef_badge,
            'pourcentageCEP'=> $pourcentageCEP,
            'pourcentagePrebadge'=> $pourcentagePrebadge,
            'pourcentageBadge'=> $pourcentageBadge,
            'regions'=> $regions
        ]);
    }

    /**
     * Nombre des chefs selon la formation
     * @Route("/nombre", name="nombre_chef")
     */
    public function nombreAction()
    {
        $em = $this->getDoctrine()->getManager();
        $chef_cep = $em->getRepository('AppBundle:Chef')->findNombreByClasse('C');
        $chef_prebadge = $em->getRepository('AppBundle:Chef')->findNombreByClasse('B');
        $chef_badge = $em->getRepository('AppBundle:Chef')->findNombreByClasse('A');

        return $this->render('default/totaux.html.twig',[
            'nombre_cep'=> $chef_cep,
            'nombre_prebadge'=> $chef_prebadge,
            'nombre_badge'=> $chef_badge,
        ]);
    }



}
