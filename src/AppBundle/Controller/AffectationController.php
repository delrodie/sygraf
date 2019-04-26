<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Affectation;
use AppBundle\Utils\CertificatUtilities;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Affectation controller.
 *
 * @Route("admin/affectation")
 */
class AffectationController extends Controller
{
    /**
     * Lists all affectation entities.
     *
     * @Route("/", name="admin_affectation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $affectations = $em->getRepository('AppBundle:Affectation')->findAll();

        return $this->render('affectation/index.html.twig', array(
            'affectations' => $affectations,
        ));
    }

    /**
     * Creates a new affectation entity.
     *
     * @Route("/new/{formation}", name="admin_affectation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, CertificatUtilities $certificatUtilities)
    {
        $formation = $request->get('formation');
        $affectation = new Affectation();
        $form = $this->createForm('AppBundle\Form\AffectationType', $affectation, ['formation'=> $formation]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $debut = $request->get('affectation_debut');
            $fin = $request->get('affectation_fin');
            if ($certificatUtilities->affectation($debut, $fin, $formation, $affectation->getRegion())){
                $affectation->setDepart($debut);
                $affectation->setArrivee($fin);
                $em->persist($affectation);
                $em->flush();
            }

            return $this->redirectToRoute('admin_affectation_new', array('formation' => $formation));
        }
        $em = $this->getDoctrine()->getManager();
        $affectations = $em->getRepository("AppBundle:Affectation")->findByFormation($formation);
        $certificats = $em->getRepository('AppBundle:Certificat')->findDisponible($formation);

        return $this->render('affectation/new.html.twig', array(
            'affectation' => $affectation,
            'affectations' => $affectations,
            'form' => $form->createView(),
            'current_page' => 'certificat',
            'certificats' => $certificats
        ));
    }

    /**
     * Finds and displays a affectation entity.
     *
     * @Route("/{id}", name="admin_affectation_show")
     * @Method("GET")
     */
    public function showAction(Affectation $affectation)
    {
        $deleteForm = $this->createDeleteForm($affectation);

        return $this->render('affectation/show.html.twig', array(
            'affectation' => $affectation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing affectation entity.
     *
     * @Route("/{id}/edit", name="admin_affectation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Affectation $affectation)
    {
        $deleteForm = $this->createDeleteForm($affectation);
        $editForm = $this->createForm('AppBundle\Form\AffectationType', $affectation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_affectation_edit', array('id' => $affectation->getId()));
        }

        return $this->render('affectation/edit.html.twig', array(
            'affectation' => $affectation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a affectation entity.
     *
     * @Route("/{id}", name="admin_affectation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Affectation $affectation)
    {
        $form = $this->createDeleteForm($affectation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($affectation);
            $em->flush();
        }

        return $this->redirectToRoute('admin_affectation_index');
    }

    /**
     * Creates a form to delete a affectation entity.
     *
     * @param Affectation $affectation The affectation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Affectation $affectation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_affectation_delete', array('id' => $affectation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
