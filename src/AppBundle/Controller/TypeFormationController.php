<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TypeFormation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Typeformation controller.
 *
 * @Route("typeformation")
 */
class TypeFormationController extends Controller
{
    /**
     * Lists all typeFormation entities.
     *
     * @Route("/", name="typeformation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $typeFormations = $em->getRepository('AppBundle:TypeFormation')->findAll();

        return $this->render('typeformation/index.html.twig', array(
            'typeFormations' => $typeFormations,
        ));
    }

    /**
     * Creates a new typeFormation entity.
     *
     * @Route("/new", name="typeformation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeFormation = new Typeformation();
        $form = $this->createForm('AppBundle\Form\TypeFormationType', $typeFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $typeFormation->setCode(strtoupper($typeFormation->getCode()));
            $em->persist($typeFormation);
            $em->flush();

            return $this->redirectToRoute('typeformation_new');
        }
        $em = $this->getDoctrine()->getManager();

        $typeFormations = $em->getRepository('AppBundle:TypeFormation')->findAll();

        return $this->render('typeformation/new.html.twig', array(
            'typeFormation' => $typeFormation,
            'typeFormations' => $typeFormations,
            'form' => $form->createView(),
            'current_page' => 'typeFormation'
        ));
    }

    /**
     * Finds and displays a typeFormation entity.
     *
     * @Route("/{id}", name="typeformation_show")
     * @Method("GET")
     */
    public function showAction(TypeFormation $typeFormation)
    {
        $deleteForm = $this->createDeleteForm($typeFormation);

        return $this->render('typeformation/show.html.twig', array(
            'typeFormation' => $typeFormation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing typeFormation entity.
     *
     * @Route("/{slug}/edit", name="typeformation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeFormation $typeFormation)
    {
        $deleteForm = $this->createDeleteForm($typeFormation);
        $editForm = $this->createForm('AppBundle\Form\TypeFormationType', $typeFormation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('typeformation_new');
        }
        $em = $this->getDoctrine()->getManager();
        $typeFormations = $em->getRepository('AppBundle:TypeFormation')->findAll();

        return $this->render('typeformation/edit.html.twig', array(
            'typeFormation' => $typeFormation,
            'typeFormations' => $typeFormations,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'current_page' => 'typeFormation'
        ));
    }

    /**
     * Deletes a typeFormation entity.
     *
     * @Route("/{id}", name="typeformation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TypeFormation $typeFormation)
    {
        $form = $this->createDeleteForm($typeFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeFormation);
            $em->flush();
        }

        return $this->redirectToRoute('typeformation_index');
    }

    /**
     * Creates a form to delete a typeFormation entity.
     *
     * @param TypeFormation $typeFormation The typeFormation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeFormation $typeFormation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('typeformation_delete', array('id' => $typeFormation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
