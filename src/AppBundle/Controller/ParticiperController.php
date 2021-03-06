<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Participer;
use AppBundle\Utils\GestionFonction;
use AppBundle\Utils\ParticiperUtilities;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Participer controller.
 *
 * @Route("participer")
 */
class ParticiperController extends Controller
{
    /**
     * Lists all participer entities.
     *
     * @Route("/", name="participer_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $participers = $em->getRepository('AppBundle:Participer')->findAll();

        return $this->render('participer/index.html.twig', array(
            'participers' => $participers,
        ));
    }

    /**
     * Creates a new participer entity.
     *
     * @Route("/new/{region}/{level}/{formation}", name="participer_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $region, $level, $formation, ParticiperUtilities $utilities, GestionFonction $gestionFonction)
    {
        $participer = new Participer(); //dump($region);die();
        $form = $this->createForm('AppBundle\Form\ParticiperType', $participer, ['region'=>$region, 'level'=>$level, 'formation'=>$formation]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //$em->persist($participer);
            //$em->flush();
            if ($utilities->create($participer)){
                $this->addFlash('notice', "Le stagiaire ".$participer->getChef()->getNom().' '.$participer->getChef()->getPrenoms()." a été bien associé à la formation de ". $participer->getFormation()->getLieu());
                //$gestionFonction->create($participer->getChef(),$participer->getFonction(),$participer->getEntite(),$participer->getFormation()->getDebut());
            }else{
                $this->addFlash('error', "Pour des raisons d'âge, le(la) stagiaire ".$participer->getChef()->getNom().' '.$participer->getChef()->getPrenoms()." n'a pas pu être associé(e) à cette formation de ". $participer->getFormation()->getType()->getLibelle());
            }

            return $this->redirectToRoute('participer_new', [
                'region'=> $region,
                'level'=> $level,
                'formation' => $formation
            ]);
        }
        // Liste des chefs concernés pas le camp
        $em = $this->getDoctrine()->getManager();
        $participants = $em->getRepository('AppBundle:Participer')->findListDesc($formation); //dump($participants);die();

        return $this->render('participer/new.html.twig', array(
            'participer' => $participer,
            'form' => $form->createView(),
            'current_page' => 'formation',
            'participants' => $participants
        ));
    }

    /**
     * Finds and displays a participer entity.
     *
     * @Route("/{id}", name="participer_show")
     * @Method("GET")
     */
    public function showAction(Participer $participer)
    {
        $deleteForm = $this->createDeleteForm($participer);

        return $this->render('participer/show.html.twig', array(
            'participer' => $participer,
            'delete_form' => $deleteForm->createView(),
            'current_page' => 'formation'
        ));
    }

    /**
     * Displays a form to edit an existing participer entity.
     *
     * @Route("/{id}/edit", name="participer_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Participer $participer)
    {
        $deleteForm = $this->createDeleteForm($participer);
        $editForm = $this->createForm('AppBundle\Form\ParticiperType', $participer);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('participer_edit', array('id' => $participer->getId()));
        }

        return $this->render('participer/edit.html.twig', array(
            'participer' => $participer,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a participer entity.
     *
     * @Route("/{id}", name="participer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Participer $participer)
    {
        $form = $this->createDeleteForm($participer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($participer);
            $em->flush();
        }

        return $this->redirectToRoute('participer_index');
    }

    /**
     * Creates a form to delete a participer entity.
     *
     * @param Participer $participer The participer entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Participer $participer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('participer_delete', array('id' => $participer->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
