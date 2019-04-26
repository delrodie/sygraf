<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Region;
use AppBundle\Utils\RegionUtilities;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Region controller.
 *
 * @Route("admin/region")
 */
class RegionController extends Controller
{
    /**
     * Lists all region entities.
     *
     * @Route("/", name="admin_region_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $regions = $em->getRepository('AppBundle:Region')->findAll();

        return $this->render('region/index.html.twig', array(
            'regions' => $regions,
        ));
    }

    /**
     * Creates a new region entity.
     *
     * @Route("/new", name="admin_region_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, RegionUtilities $utilities)
    {
        $region = new Region();
        $form = $this->createForm('AppBundle\Form\RegionType', $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if (!$utilities->duplicate($region->getLibelle())){
                $region->setLibelle(strtoupper($region->getLibelle()));
                $em->persist($region);
                $em->flush();

                $this->addFlash('notice', 'La région '.$region->getLibelle().' a été enregistrée avec succès');
            }else{
                $this->addFlash('error', "Echèc de l'enregistrement!!! la région ".$region->getLibelle()." existe déjà.");
            }

            return $this->redirectToRoute('admin_region_new');
        }
        $em = $this->getDoctrine()->getManager();
        $regions = $em->getRepository('AppBundle:Region')->findAll();

        return $this->render('region/new.html.twig', array(
            'region' => $region,
            'regions' => $regions,
            'form' => $form->createView(),
            'current_page' => 'region'
        ));
    }

    /**
     * Finds and displays a region entity.
     *
     * @Route("/{id}", name="admin_region_show")
     * @Method("GET")
     */
    public function showAction(Region $region)
    {
        $deleteForm = $this->createDeleteForm($region);

        return $this->render('region/show.html.twig', array(
            'region' => $region,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing region entity.
     *
     * @Route("/{slug}/edit", name="admin_region_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Region $region, RegionUtilities $utilities)
    {
        $deleteForm = $this->createDeleteForm($region);
        $editForm = $this->createForm('AppBundle\Form\RegionType', $region);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $region->setLibelle(strtoupper($region->getLibelle()));
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', "Modification effectuée avec succès!");

            return $this->redirectToRoute('admin_region_new');
        }
        $em = $this->getDoctrine()->getManager();
        $regions = $em->getRepository('AppBundle:Region')->findAll();

        return $this->render('region/edit.html.twig', array(
            'region' => $region,
            'regions' => $regions,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'current_page' => 'region'
        ));
    }

    /**
     * Deletes a region entity.
     *
     * @Route("/{id}", name="admin_region_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Region $region)
    {
        $form = $this->createDeleteForm($region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($region);
            $em->flush();
        }

        return $this->redirectToRoute('admin_region_index');
    }

    /**
     * Creates a form to delete a region entity.
     *
     * @param Region $region The region entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Region $region)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_region_delete', array('id' => $region->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
