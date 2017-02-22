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

class NoteController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function showNote() {
        try {
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository('FirstBundle:note')->findAll();
        } catch (\Doctrine\DBAL\DBALException $e) {
            $this->addFlash('bad', 'erreur de connection a la DB');
            return $this->redirect($this->generateUrl('home'));
        }

        return $this->render('FirstBundle:Note:index.html.twig', array('notes' => $product));
    }

    /**
     * @Route("/newNote", name="newNote")
     */
    public function newNote(Request $request) {
        $task = new note();

        return $this->modifyNote($request, $task);
    }

    /**
     * @Route("/deleteNote/{id}", name="deleteNote")
     */
    public function deleteNote(note $note) {
        $em = $this->getDoctrine()->getManager();
        $toDel = $em->getRepository('FirstBundle:note')->find($note);
        try {
            $em->remove($toDel);
            $em->flush();
        } catch (\Doctrine\DBAL\DBALException $e) {
            $this->addFlash('bad', 'La note n a pas pu être supprimée');
            return $this->redirect($this->generateUrl('home'));
        }

        $this->addFlash('notice', 'La note a bien été supprimée');
        return $this->redirect($this->generateUrl('home'));
    }

    /**
     * @Route("/modifyNote/{id}", name="modifyNote")
     */
    public function modifyNote(Request $request, note $task) {
        $em = $this->getDoctrine()->getManager();

        $choices = $em->getRepository('FirstBundle:categorie')->findAll();

        $form = $this->createFormBuilder($task)
            ->add('title', TextType::class, array('label' => 'Titre'))
            ->add('content', TextType::class, array('label' => 'Contenu'))
            ->add('categorie', ChoiceType::class, array('label' => 'Catégorie',
                'choices'=>$choices,
                'choice_label' => function($cat, $key, $index) {
                    return $cat->getName();
                }))
            ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
            ->getForm();

        $form->handleRequest($request);
        $task = $form->getData();

        if($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($task);
                $em->flush();
            } catch(\Doctrine\DBAL\DBALException $e) {
                $this->addFlash('bad', 'La note n a pas pu être editée');
                return $this->redirect($this->generateUrl('home'));
            }
            $this->addFlash('notice', 'La note a bien été engeristée');
            return $this->redirect($this->generateUrl('home'));
        }

        return $this->render('FirstBundle:Note:newNote.html.twig', array('form' => $form->createView()));
    }
}
