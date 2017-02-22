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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\JsonResponse;

class APINoteController extends Controller
{
    /**
     * @Route("/api/note")
     * @Method("GET")
     */
    public function getNotesAction() {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('FirstBundle:note')->findAll();

        $serializer = $this->get('serializer');
        return new JsonResponse($serializer->serialize($product, 'json'));
    }

    /**
     * @Route("/api/note/{id}")
     * @Method("GET")
     */
    public function getNoteAction(note $note) {
        $serializer = $this->get('serializer');
        return new JsonResponse($serializer->serialize($note, 'json'));
    }

    /**
     * @Route("/api/note")
     * @Method("POST")
     */
     public function newNoteAction(Request $request) {
         $note = new note;
         return $this->updateNoteAction($request, $note);
     }

     /**
      * @Route("/api/note/{id}")
      * @Method("PUT")
      */
      public function updateNoteAction(Request $request, note $note) {
          $data = json_decode($request->getContent(), true);

          $em = $this->getDoctrine()->getManager();
          $cat = $em->getRepository('FirstBundle:categorie')->find($data['categorie']);

          $note->setTitle($data['title']);
          $note->setContent($data['content']);
          $note->setCategorie($cat);

          $em = $this->getDoctrine()->getManager();
          $em->persist($note);
          $em->flush();

          return new Response();
      }

      /**
       * @Route("/api/note/{id}")
       * @Method("DELETE")
       */
       public function deleteNoteAction(Request $request, note $note) {
           $em = $this->getDoctrine()->getManager();
           $toDel = $em->getRepository('FirstBundle:note')->find($note);

           $em->remove($toDel);
           $em->flush();

           return new Response();
       }

}
