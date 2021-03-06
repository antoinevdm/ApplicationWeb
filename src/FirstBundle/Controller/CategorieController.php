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
        //try to connect to db and taking "categorie" data from it
        try {
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository('FirstBundle:categorie')->findAll();
        } catch (\Doctrine\DBAL\DBALException $e) {
            $this->addFlash('bad', 'erreur de connection a la DB');
            return $this->redirect($this->generateUrl('listeCategorie'));
        }

        return $this->render('FirstBundle:Note:catergorie.html.twig',
        array('categories' => $product));
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
        try {
            $em = $this->getDoctrine()->getManager();
            $toDel = $em->getRepository('FirstBundle:categorie')->find($cat);
            $em->remove($toDel);
            $em->flush();
        } catch (\Doctrine\DBAL\DBALException $e) {
            $this->addFlash('bad', 'La catégorie n a pas pu être supprimée');
            return $this->redirect($this->generateUrl('listeCategorie'));
        }
        $this->addFlash('notice', 'La catégorie a bien été supprimée');
        return $this->redirect($this->generateUrl('listeCategorie'));
    }

    /**
     * @Route("/modifyCategorie/{id}", name="modifyCategorie")
     */
    public function modifyCategorie(Request $request, categorie $task) {
        //Form builder for creating a new categorie
        $form = $this->createFormBuilder($task)
            ->add('name', TextType::class,
                array('label' => 'Nom de la catégorie'))
            ->add('save', SubmitType::class, array('label' => 'Sauvegarder'))
            ->getForm();

        $form->handleRequest($request);
        $task = $form->getData();

        //Push it in db when form is completed
        if($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($task);
                $em->flush();
            } catch(\Doctrine\DBAL\DBALException $e) {
                $this->addFlash('bad', 'La catégorie na pas pu être editée');
                return $this->redirect($this->generateUrl('listeCategorie'));
            }
            $this->addFlash('notice', 'La catégorie a bien été engeristée');
            //go back to previous page
            return $this->redirect($this->generateUrl('listeCategorie'));
        }

        return $this->render('FirstBundle:Note:newCategorie.html.twig',
        array('form' => $form->createView()));
    }
}
