<?php
// src/Controller/ProjectController.php

namespace App\Controller;

use App\Entity\Ticket;
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
     * @Route("/project/{id}", name="show_project")
     */
    public function showt ($id)
    {
        $entityManager = $this -> getDoctrine($id)
	        -> getManager();
	$project = $entityManager->getRepository(Projects::class)
	       	-> find($id);

        if (!$project) {
            throw $this->createNotFoundException(
                'No project found for id '.$id
            );
        }

	return $this->render('Project/proj.html.twig', [
            'proj' => $project , ]);
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
        return $this->render('Project/number.html.twig', array(
            'project' => $proj
    ));
    }

     /**
     * @Route("/createProj", name="creat_proj")
     */
    public function new ( Request $request )
    {
	$proj = new Projects ();
//	$proj -> setCreator ( 99 );
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
