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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ProjectController extends AbstractController
{	

    /**
     * @IsGranted("ROLE_USER")	 
     * @Route("/project/{id}", name="show_project")
     */
    public function showt ( Request $request, $id) : Response
    {
        $entityManager = $this -> getDoctrine($id)
	        -> getManager();
	$proj = $entityManager->getRepository(Projects::class)
	       	-> find($id);

        if (!$proj) {
            throw $this->createNotFoundException(
                'No project found for id '.$id
            );
        }

	return $this->render('Project/proj.html.twig',
              array('proj' => $proj));
    }
 
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/list", name="list_proj")
     */
    public function listProj () : Response
    {
        $userid = $this-> getUser()-> getId();

        $user = $this-> getDoctrine($userid)
            -> getRepository(User::class)
            -> find ($userid);

        $proj = $this -> getDoctrine()
	    -> getManager()
	    -> getRepository ( Projects :: class )
	    -> findAll();
        return $this->render('Project/number.html.twig', array(
		'project' => $proj,
		'user' => $user,
    ));
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/createProj", name="creat_proj")
     */
    public function new ( Request $request )
    {
	$proj = new Projects ();
        $author = $this->getUser()->getId();
	$form = $this -> createForm ( ProjType :: class , $proj );
      
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
	    $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->find($author);
	    $proj->setAuthor($user);
	    $em->persist($proj);
	    $em->persist($user);
	    $em->flush();
	   
            return $this->redirectToRoute('list_proj');
        }


        return $this -> render ( 'Project/new.html.twig' , [
		'form' => $form -> createView (),
	]);
    }  

    /**
      * @IsGranted("ROLE_USER")
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
      * @IsGranted("ROLE_USER")
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
