<?php

namespace FirstBundle\Controller;

use FirstBundle\Entity\note;
use FirstBundle\Entity\categorie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class APICategorieController extends Controller
{

    /**
     * @Route("/api/categorie")
     * @Method("GET")
     */
    public function getCategoriesAction() {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('FirstBundle:categorie')->findAll();

        //send a serialized json object for each note
        $serializer = $this->get('serializer');
        return new JsonResponse($serializer->serialize($product, 'json'));
    }

    /**
     * @Route("/api/categorie/{id}")
     * @Method("GET")
     */
    public function getCategorieAction(categorie $cat) {
        $serializer = $this->get('serializer');
        return new JsonResponse($serializer->serialize($cat, 'json'));
    }

    /**
     * @Route("/api/categorie")
     * @Method("POST")
     */
     public function newCategorieAction(Request $request) {
         $cat = new categorie;
         return $this->updateCategorieAction($request, $cat);
     }

     /**
      * @Route("/api/categorie/{id}")
      * @Method("PUT")
      */
      public function updateCategorieAction(Request $request, categorie $cat) {
          //decode json object on a local vairable
          $data = json_decode($request->getContent(), true);

          //update decoded object's data
          $cat->setName($data['name']);

          $em = $this->getDoctrine()->getManager();
          $em->persist($cat);
          $em->flush();

          return new Response();
      }

      /**
       * @Route("/api/categorie/{id}")
       * @Method("DELETE")
       */
       public function deleteNoteAction(Request $request, categorie $cat) {
           $em = $this->getDoctrine()->getManager();
           $toDel = $em->getRepository('FirstBundle:categorie')->find($cat);

           $em->remove($toDel);
           $em->flush();

           return new Response();
       }
}
