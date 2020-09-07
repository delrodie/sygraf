<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chef;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Chef controller.
 *
 * @Route("chef")
 */
class ChefController extends Controller
{
    /**
     * Lists all chef entities.
     *
     * @Route("/", name="chef_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $chefs = $em->getRepository('AppBundle:Chef')->findAll();

        return $this->render('chef/index.html.twig', array(
            'chefs' => $chefs,
        ));
    }

    /**
     * Creates a new chef entity.
     *
     * @Route("/new", name="chef_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $chef = new Chef();
        $form = $this->createForm('AppBundle\Form\ChefType', $chef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // Si la case Titularisation est cochée alors affecté la classe A
            if ($chef->getTitularisation()){
                $chef->setClasse('A');
            }
            // verifcation de la date de naissance
            // Si l'age est inférieur à 21ans alors echec
            $date_anniversaire = date_create($chef->getDatenais());
            $aujourdhui = date_create(date('Y-m-d'));
            $difference = date_diff($date_anniversaire,$aujourdhui);
            $age = $difference->format('%y');

            if ($age < 21){
                $this->addFlash('error', "Echec! Ce chef n'a pas encore 21 ans. Impossible de l'enregistrer dans le système.");
                return $this->redirectToRoute('chef_new');
            }

            $em->persist($chef);
            $em->flush();

            return $this->redirectToRoute('chef_show', array('slug' => $chef->getSlug()));
        }

        // Liste ordonnée des chefs
        $em = $this->getDoctrine()->getManager();
        $chefs = $em->getRepository('AppBundle:Chef')->findListeOrd();

        return $this->render('chef/new.html.twig', array(
            'chef' => $chef,
            'chefs' => $chefs,
            'form' => $form->createView(),
            'current_page' => 'chef',
        ));
    }

    /**
     * Finds and displays a chef entity.
     *
     * @Route("/{slug}", name="chef_show")
     * @Method("GET")
     */
    public function showAction(Chef $chef)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($chef);

        $participations = $em->getRepository('AppBundle:Participer')->findBy(['chef'=>$chef->getId()]); //dump($participations);die();

        return $this->render('chef/show.html.twig', array(
            'chef' => $chef,
            'delete_form' => $deleteForm->createView(),
            'current_page' => 'chef',
            'participations' => $participations,
        ));
    }

    /**
     * Displays a form to edit an existing chef entity.
     *
     * @Route("/{slug}/edit", name="chef_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Chef $chef)
    {
        $deleteForm = $this->createDeleteForm($chef);
        $editForm = $this->createForm('AppBundle\Form\ChefType', $chef);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $old_titularisation = $request->get('oldTitularisation');
            // Si Titularisation est cochée et la classe differente de C
            // Alors affecte classe A sinon descativer la titularisation
            if ($chef->getTitularisation()){
                if ($chef->getClasse() != 'C'){
                    $chef->setClasse('A');
                }else{
                    $chef->setTitularisation(false);
                }
            }elseif ($chef->getTitularisation() != $old_titularisation){
                $participation = $em->getRepository("AppBundle:Participer")->findOneBy(['chef'=>$chef->getId()],['id'=>"DESC"]);
                if (!$participation){
                    $classe = null;
                }else{
                    $formation = $em->getRepository("AppBundle:Formation")->findOneBy(['id'=>$participation->getFormation()->getId()]);
                    $classe = $formation->getType()->getCode();
                } //dump($classe);die();
                $chef->setClasse($classe);
            }
            $em->flush();

            return $this->redirectToRoute('chef_show', array('slug' => $chef->getSlug()));
        }

        // Liste ordonnée des chefs
        $em = $this->getDoctrine()->getManager();
        $chefs = $em->getRepository('AppBundle:Chef')->findListeOrd();

        return $this->render('chef/edit.html.twig', array(
            'chef' => $chef,
            'chefs' => $chefs,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'current_page'=> 'chef'
        ));
    }

    /**
     * Deletes a chef entity.
     *
     * @Route("/{id}", name="chef_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Chef $chef)
    {
        $form = $this->createDeleteForm($chef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($chef);
            $em->flush();
        }

        return $this->redirectToRoute('chef_new');
    }

    /**
     * Creates a form to delete a chef entity.
     *
     * @param Chef $chef The chef entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Chef $chef)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('chef_delete', array('id' => $chef->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
