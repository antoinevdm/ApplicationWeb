<?php

namespace FirstBundle\Controller;

use FirstBundle\Entity\note;
use FirstBundle\Entity\categorie;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class CategorieController extends Controller
{
    /**
     * @Route("/listeCategorie", name="listeCategorie")
     */
    public function showCategorie() {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('FirstBundle:categorie')->findAll();

        return $this->render('FirstBundle:Note:catergorie.html.twig', array('categories' => $product));
    }

    /**
     * @Route("/newCategorie", name="newCategorie")
     */
    public function newCategorie(Request $request) {
        $task = new categorie();

        return $this->modifyCategorie($request, $task);
    }

    /**
     * @Route("/deleteCategorie/{id}", name="deleteCategorie")
     */
    public function deleteCategorie(categorie $cat) {
        $em = $this->getDoctrine()->getManager();
        $toDel = $em->getRepository('FirstBundle:categorie')->find($cat);

        $em->remove($toDel);
        $em->flush();

        return $this->redirect($this->generateUrl('listeCategorie'));
    }

    /**
     * @Route("/modifyCategorie/{id}", name="modifyCategorie")
     */
    public function modifyCategorie(Request $request, categorie $task) {
        $form = $this->createFormBuilder($task)
            ->add('name', TextType::class, array('label' => 'Nom de la catégorie'))
            ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
            ->getForm();

        $form->handleRequest($request);
        $task = $form->getData();

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            $this->addFlash('notice', 'La catégorie a bien été engeristée');
            return $this->redirect($this->generateUrl('listeCategorie'));
        }

        return $this->render('FirstBundle:Note:newCategorie.html.twig', array('form' => $form->createView()));
    }
}
