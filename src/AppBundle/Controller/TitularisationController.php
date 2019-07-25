<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Titularisation;
use AppBundle\Utils\GestionFonction;
use AppBundle\Utils\GestionTitularisation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Titularisation controller.
 *
 * @Route("titularisation")
 */
class TitularisationController extends Controller
{
    /**
     * Lists all titularisation entities.
     *
     * @Route("/", name="titularisation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        //$titularisations = $em->getRepository('AppBundle:Titularisation')->findAll();
        $chefs = $em->getRepository('AppBundle:Chef')->findListBadgiste();

        return $this->render('titularisation/index.html.twig', array(
            //'titularisations' => $titularisations,
            'chefs' => $chefs,
            'current_page' => 'titularisation',
        ));
    }

    /**
     * Creates a new titularisation entity.
     *
     * @Route("/new/{chef}", name="titularisation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $chef, GestionFonction $gestionFonction, GestionTitularisation $gestionTitularisation)
    {
        $titularisation = new Titularisation();
        $form = $this->createForm('AppBundle\Form\TitularisationType', $titularisation, ['chef'=> $chef]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository("AppBundle:Titularisation")->findOneBy(['chef'=>$titularisation->getChef()->getId(), 'buchette'=>$titularisation->getBuchette()]);
            if ($exist){
                $this->addFlash(
                    'error',
                    $titularisation->getChef()->getNom().' '.$titularisation->getChef()->getPrenoms()." a déjà été titularisé(e) aux ".$titularisation->getBuchette()." bûchettes!"
                );
                return $this->redirectToRoute('titularisation_new', array('chef' => $titularisation->getChef()->getSlug()));
            }
            if ($titularisation->getFonction()){
                $chef = $titularisation->getChef();
                $fonction = $titularisation->getFonction();
                $entite = $titularisation->getEntite();
                $date = $titularisation->getDateTitu();
                if (!$gestionFonction->create($chef,$fonction,$entite,$date)){
                    $this->addFlash('error',"Attention tous les champs doivent être renseignés");
                    return $this->redirectToRoute('titularisation_new', array('chef' => $titularisation->getChef()->getSlug()));
                }
            } else{
                $this->addFlash('error',"Attention les champs relatifs à la fonction doivent être renseignés");
                return $this->redirectToRoute('titularisation_new', array('chef' => $titularisation->getChef()->getSlug()));
            }
            if ($titularisation->getBuchette()) $gestionTitularisation->majBuchette($chef,$titularisation->getBuchette());
            $em->persist($titularisation);
            $em->flush();

            // Mise a jour de la fonction concernée par la titularisation flag 2
            $gestionFonction->maj($chef,2,$titularisation->getId());

            $this->addFlash(
                'notice',
                "La titularisation de(d') ".$titularisation->getChef()->getNom().' '.$titularisation->getChef()->getPrenoms().' a été enregistrée avec succès'
            );

            return $this->redirectToRoute('titularisation_new', array('chef' => $titularisation->getChef()->getSlug()));
        }
        $em = $this->getDoctrine()->getManager();
        $titulaire = $em->getRepository("AppBundle:Chef")->findOneBy(['slug'=>$chef]);
        //$titularisations = $em->getRepository("AppBundle:Titularisation")->findBy(['chef'=>$titulaire->getId()]); dump($titulaire);die();
        //dump($titulaire);die();
        return $this->render('titularisation/new.html.twig', array(
            'titularisation' => $titularisation,
            'titulaire' => $titulaire,
            'form' => $form->createView(),
            'current_page' => 'titularisation',
        ));
    }

    /**
     * Finds and displays a titularisation entity.
     *
     * @Route("/{id}", name="titularisation_show")
     * @Method("GET")
     */
    public function showAction(Titularisation $titularisation)
    {
        $deleteForm = $this->createDeleteForm($titularisation);

        return $this->render('titularisation/show.html.twig', array(
            'titularisation' => $titularisation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing titularisation entity.
     *
     * @Route("/{id}/edit", name="titularisation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Titularisation $titularisation, GestionFonction $gestionFonction, GestionTitularisation $gestionTitularisation)
    {
        $deleteForm = $this->createDeleteForm($titularisation);
        $editForm = $this->createForm('AppBundle\Form\TitularisationType', $titularisation, ['chef'=>$titularisation->getChef()->getSlug()]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            if ($titularisation->getFonction()){
                $chef = $titularisation->getChef();
                $fonction = $titularisation->getFonction();
                $entite = $titularisation->getEntite();
                $date = $titularisation->getDateTitu();
                // Identification de la fonction
                //$fonctions = $em->getRepository("AppBundle:Fonction")->findOneBy(['chef'=>$chef->getId(), 'flag'=>2, 'reference'=>$titularisation->getId() ]);
                //dump($fonctions);die();
                if (!$gestionFonction->update($chef,$fonction,$entite,$date, $titularisation->getId())){
                    $this->addFlash('error',"Attention tous les champs doivent être renseignés");
                    return $this->redirectToRoute('titularisation_new', array('chef' => $titularisation->getChef()->getSlug()));
                }
            } else{
                $this->addFlash('error',"Attention les champs relatifs à la fonction doivent être renseignés");
                return $this->redirectToRoute('titularisation_new', array('chef' => $titularisation->getChef()->getSlug()));
            }
            if ($titularisation->getBuchette()) $gestionTitularisation->majBuchette($chef,$titularisation->getBuchette());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('titularisation_edit', array('id' => $titularisation->getId()));
        }
        $em = $this->getDoctrine()->getManager();
        $titulaire = $em->getRepository("AppBundle:Chef")->findOneBy(['slug'=>$titularisation->getChef()->getSlug()]);

        return $this->render('titularisation/edit.html.twig', array(
            'titularisation' => $titularisation,
            'titulaire' => $titulaire,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'current_page' => 'titularisation',
        ));
    }

    /**
     * Deletes a titularisation entity.
     *
     * @Route("/{id}", name="titularisation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Titularisation $titularisation, GestionFonction $gestionFonction, GestionTitularisation $gestionTitularisation)
    {
        $idDeleted = $titularisation->getId();
        $chef = $titularisation->getChef();
        $form = $this->createDeleteForm($titularisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { //die('ici');
            $em = $this->getDoctrine()->getManager();
            $em->remove($titularisation);
            $em->flush();
            // Suppression de la fonction correspondante
            $gestionFonction->delete($idDeleted);
            //Mise a jour de la table chef
            $buchette = '';
            $gestionTitularisation->majBuchette($chef,$buchette);

        }

        return $this->redirectToRoute('titularisation_index');
    }

    /**
     * Creates a form to delete a titularisation entity.
     *
     * @param Titularisation $titularisation The titularisation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Titularisation $titularisation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('titularisation_delete', array('id' => $titularisation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
