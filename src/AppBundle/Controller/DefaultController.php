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
        return $this->render('default/index.html.twig', [
            'current_page' => 'accueil',
            'cep' => $cep,
            'prebadge' => $prebadge,
            'badge' => $badge
        ]);
    }
}
