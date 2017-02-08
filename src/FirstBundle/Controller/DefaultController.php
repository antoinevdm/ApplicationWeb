<?php

namespace FirstBundle\Controller;

use FirstBundle\Entity\note;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction() {
        return $this->render('FirstBundle:Note:index.html.twig');
    }

    /**
     * @Route("/newNote", name="newNote")
     */
    public function newNoteAction(Resquest $request) {
        $task = new note();

        $form = $this->createFormBuilder($task)
            ->add('title', TextType::class, array('label' => 'Titre'))
            ->add('content', TextType::class, array('label' => 'Contenu'))
            ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
            ->getForm();

        $form->handleRequest($request);
        $task = $form->getData();

        if($form->isValide()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            return new Response('La note a été ajoutée avec succés !');
        }

        return $this->render('FirstBundle:Note:newNote.html.twig', array('form' => $form->creatView()));

    }

}
