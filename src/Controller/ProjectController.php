<?php
// src/Controller/ProjectController.php

namespace App\Controller;

use App\Entity\User ;
use App\Entity\Projects ;
use App\Form\Type\ProjType ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface ;
use Symfony\Component\HttpFoundation\Request ;

class ProjectController extends AbstractController
{
    /**
     * @Route("/project")
     */
    public function createProject () : Response
    {
        $entityManager = $this -> getDoctrine () -> getManager ();

        $proj = new Projects ();
        $proj -> setNameProj ( 'CoBaKa' );
        $proj -> setCreator ( 99 );
        $entityManager -> persist ( $proj );

        $entityManager -> flush ();

	return new Response ( 'Saved new product with id ' . $proj -> getId ());
    }

 /**
     * @Route("/user")
     */
    public function user () : Response
    {
        $entityManager = $this -> getDoctrine () -> getManager ();

        $user = new User ();
        $user -> setUsername ( 'Lord' );
        $user -> setEmail ('sofa.zu@mail.ru');
        $user -> setPassword ( 99999 );
        $user -> setRoles('ROLE ADMIN');
        $entityManager -> persist ( $user );

        $entityManager -> flush ();

        return new Response ( 'Saved new product with id ' . $user -> getId ());
    }


     /**
      * @Route("/project/{id}")
      */
     public function show ( $id ) : Response
     {
         $project = $this -> getDoctrine ($id)
            -> getRepository ( Projects :: class )
            -> find ( $id );

         if ( ! $project ) {
            throw $this -> createNotFoundException (
               'No product found for id ' . $id
             );
	 }

    //      return $this->render('Project/foo.html.twig', [ 'id' => $project -> getId() ,
 //	    'name' => $project -> getNameProj () ,
//	    'creat' => $project -> getCreator () ,
	 //        ]);
	 return new Response('You have visited the project : '.$project->getNameProj() ."<br>Creator:" . $project->getCreator ());
     } 

    /**
     * @Route("/list", name="list_proj")
     */
    public function listProj () : Response
    {
        $proj = $this -> getDoctrine()
	    -> getManager()
	    -> getRepository ( Projects :: class )
	    -> findAll();
//		var_dump($proj[1]);
     //  return array($proj);
        return $this->render('Project/number.html.twig', array(
            'project' => $proj,
    ));
    }

     /**
     * @Route("/createProj", name="creat_proj")
     */
    public function new ( Request $request )
    {
	$proj = new Projects ();
//	$proj -> getNameProj ();
	$proj -> setCreator ( 99 );
	$form = $this -> createForm ( ProjType :: class , $proj );

         $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $em = $this->getDoctrine()->getManager();
            $em->persist($proj);
            $em->flush();
            return $this->redirectToRoute('list_proj');
        }


        return $this -> render ( 'Project/new.html.twig' , [
		'form' => $form -> createView (),
	]);
    }  

    /**
      * @Route("/editProj/{id}", name="edit_proj")
      */
     public function edit ( Request $request, $id ) : Response
     {
         $proj = $this -> getDoctrine ($id)
            -> getRepository ( Projects :: class )
            -> find ( $id );

         if ( ! $proj ) {
            throw $this -> createNotFoundException (
               'No project found for id ' . $id
             );
	 }

	 $proj -> getNameProj ();
         $form = $this -> createForm ( ProjType :: class , $proj );

         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($proj);
            $em->flush();
            return $this->redirectToRoute('list_proj');
         }


         return $this -> render ( 'Project/edit.html.twig' , [
                'form' => $form -> createView (), 'name' => $proj -> getNameProj () ,
           ]);
     }

     
     /**
      * @Route("/delete_proj/{id}", name="delete_proj")
      */
     public function delete_proj ( Request $request, $id ) : Response
     {
         $proj = $this -> getDoctrine ($id)
            -> getRepository ( Projects :: class )
            -> find ( $id );
         $em = $this->getDoctrine()->getManager();  
	 $em -> remove($proj);
         $em -> flush();
         
         return $this->redirectToRoute('list_proj');
      }

}
