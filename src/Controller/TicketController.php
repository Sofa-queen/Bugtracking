<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Ticket;
use App\Entity\User ;
use App\Entity\Projects ;
use App\Form\Type\TickType ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface ;
use Symfony\Component\HttpFoundation\Request ;

class TicketController extends AbstractController
{
     /**
     * @Route("/newTick/{proj_id}", name="creat_ticket")
     */
    public function new ( $proj_id, Request $request )
    {
	 $entityManager = $this -> getDoctrine($proj_id)
                -> getManager();
        $project = $entityManager->getRepository(Projects::class)
		-> find($proj_id);

	$ticket = new Ticket ();
        $ticket -> setCreator ( 9 );
        $form = $this -> createForm ( TickType :: class , $ticket );
                                      
	$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
	    $em = $this->getDoctrine()->getManager();

            $tagsString = $request -> get('ticket')['tags_string'];
	    $tags = array_map(function($value) { return trim($value); }, explode(',', $tagsString));

            foreach ($tags as $tagName) {
		    $tag = new Tag();
		    $tag -> setName($tagName);
		    $em -> persist($tag);
		    $ticket -> addTag($tag);
	    }

	    $project = $em -> getRepository(Projects::class) -> find($proj_id);
	
	    $ticket->setProject($project);

            $em -> persist($project);
            $em -> persist($ticket);
            $em -> flush();

            return $this->redirectToRoute('show_project', ['id' => $proj_id]);
        }


	return $this -> render ( 'Ticket/ticknew.html.twig' , [
		'proj' => $project,
	        'ticket' => $ticket,      
		'form' => $form -> createView (),
        ]);
    }

     /**
      * @Route("/{project_id}/editTick/{id}", name="edit_ticket",  methods={"GET","POST"})
      */
     public function edit (  Request $request, $id, $project_id ) : Response
     {
         $entityManager = $this -> getDoctrine($project_id)
                -> getManager();
         $project = $entityManager->getRepository(Projects::class)
                -> find($project_id);
//	 $projectId = $request->query->get('project_id');
         $tick = $this -> getDoctrine ($id)
            -> getRepository ( Ticket :: class )
            -> find ( $id );

         if ( ! $tick ) {
            throw $this -> createNotFoundException (
               'No project found for id ' . $id
             );
         }

	 $tick -> getName (); 
         $form = $this -> createForm ( TickType :: class , $tick );

         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tick);
            $em->flush();
            return $this->redirectToRoute('show_project', ['id' => $project_id]);
         }


         return $this -> render ( 'Ticket/edit.html.twig' , [
                'form' => $form -> createView (), 'name' => $tick -> getName () , 'proj' => $project,
           ]);
     }

      /**
      * @Route("/{project_id}/delete_ticket/{id}", name="delete_ticket")
      */
     public function delete_tick ( Request $request, $id, $project_id ) : Response
     {
	   $projectId = $request->query->get('project_id');
	   $tick = $this -> getDoctrine ($id)
            -> getRepository ( Ticket :: class )
	    -> find ( $id );

	    if (!$tick) {
		    throw $this->createNotFoundException(
			    'No ticket found for id '.$id
                );
	    }
         $em = $this->getDoctrine()->getManager();  
	 $em -> remove($tick);
         $em -> flush();


         return $this->redirectToRoute('show_project', ['id' => $projectId]);
      }

      /**
      * @Route("/Tick/{id}", name="ticket")
      */
     public function ticket ( Request $request, $id ) : Response
     {
//         $entityManager = $this -> getDoctrine($proj_id)
//                -> getManager();
//         $project = $entityManager->getRepository(Projects::class)
//		-> find($proj_id);

         $tick = $this -> getDoctrine ($id)
            -> getManager()
            -> getRepository ( Ticket :: class )
            -> find ( $id );

         if ( ! $tick ) {
            throw $this -> createNotFoundException (
               'No project found for id ' . $id
             );
	 }

	  return $this->render('Ticket/tick.html.twig', [
//              'proj' => $project,		  
              'tick' => $tick, ]);
    }
}
