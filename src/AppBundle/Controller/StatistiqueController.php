<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StatistiqueController
 * @Route("/statistques")
 */
class StatistiqueController extends Controller
{
    /**
     * Nombre selon la formation
     * @Route("/{level}/{region}", name="statitisque_region")
     */
    public function indexAction($level, $region)
    {
        $em = $this->getDoctrine()->getManager();
        $nombre = $em->getRepository('AppBundle:Chef')->findNombreByClasse($level, $region);
        return $this->render('default/nombre.html.twig',[
            'nombre' => $nombre,
        ]);
    }

    /**
     * Les totaux des chefs formés
     * @Route("/{region}", name="statistique_totaux")
     */
    public function totauxAction($region)
    {
        $em = $this->getDoctrine()->getManager();
        $chef_cep = $em->getRepository('AppBundle:Chef')->findNombreByClasse('C', $region);
        $chef_prebadge = $em->getRepository('AppBundle:Chef')->findNombreByClasse('B', $region);
        $chef_badge = $em->getRepository('AppBundle:Chef')->findNombreByClasse('A', $region);

        $totaux = $chef_badge + $chef_prebadge + $chef_cep;
        //$totaux = 3;

        return $this->render('default/nombre.html.twig',[
            'nombre' => $totaux
        ]);
    }
}
