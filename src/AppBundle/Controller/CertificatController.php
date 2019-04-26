<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Certificat;
use AppBundle\Utils\CertificatUtilities;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Certificat controller.
 *
 * @Route("admin/certificat")
 */
class CertificatController extends Controller
{
    /**
     * Lists all certificat entities.
     *
     * @Route("/", name="admin_certificat_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $certificats = $em->getRepository('AppBundle:Certificat')->findAll();

        return $this->render('certificat/index.html.twig', array(
            'certificats' => $certificats,
        ));
    }

    /**
     * Creates a new certificat entity.
     *
     * @Route("/new", name="admin_certificat_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, CertificatUtilities $utilities)
    {
        $certificat = new Certificat();
        $form = $this->createForm('AppBundle\Form\CertificatType', $certificat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $code1 = $request->get('code1');
            $code2 = $request->get('code2');

            //$em->persist($certificat);
            //$em->flush();
            if ($utilities->create($code1, $code2, $certificat->getFormation()->getId())){
                $this->addFlash('notice', 'Certificats crées avec succès!');
            } else{
                $this->addFlash('error', "L'enregistrement des certificats a échoué. Veuillez vous assurer que les certificats n'existe pas déjà!");
            }

            return $this->redirectToRoute('admin_certificat_new');
        }
        $em = $this->getDoctrine()->getManager();
        $certificats = $em->getRepository('AppBundle:Certificat')->findListDesc();

        return $this->render('certificat/new.html.twig', array(
            'certificat' => $certificat,
            'certificats' => $certificats,
            'form' => $form->createView(),
            'current_page' => 'certificat'
        ));
    }

    /**
     * Finds and displays a certificat entity.
     *
     * @Route("/{id}", name="admin_certificat_show")
     * @Method("GET")
     */
    public function showAction(Certificat $certificat)
    {
        $deleteForm = $this->createDeleteForm($certificat);

        return $this->render('certificat/show.html.twig', array(
            'certificat' => $certificat,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing certificat entity.
     *
     * @Route("/{id}/edit", name="admin_certificat_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Certificat $certificat)
    {
        $deleteForm = $this->createDeleteForm($certificat);
        $editForm = $this->createForm('AppBundle\Form\CertificatType', $certificat);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_certificat_new');
        }
        $em = $this->getDoctrine()->getManager();
        $certificats = $em->getRepository('AppBundle:Certificat')->findListDesc();

        return $this->render('certificat/edit.html.twig', array(
            'certificat' => $certificat,
            'certificats' => $certificats,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'current_page' => 'certificat'
        ));
    }

    /**
     * Deletes a certificat entity.
     *
     * @Route("/{id}", name="admin_certificat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Certificat $certificat)
    {
        $form = $this->createDeleteForm($certificat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($certificat);
            $em->flush();
        }

        return $this->redirectToRoute('admin_certificat_index');
    }

    /**
     * Creates a form to delete a certificat entity.
     *
     * @param Certificat $certificat The certificat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Certificat $certificat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_certificat_delete', array('id' => $certificat->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
