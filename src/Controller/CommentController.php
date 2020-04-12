<?php

namespace App\Controller;

use App\Entity\User ;
use App\Entity\Projects ;
use App\Entity\Ticket;
use App\Entity\Comment;
use App\Form\Type\CommentType ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface ;
use Symfony\Component\HttpFoundation\Request ;

class CommentController extends AbstractController
{

     /**
      * @Route("/{proj_id}/new_comment/{id}", name="newcomment")
      */
     public function newcomment ($proj_id, Request $request, $id ) : Response
     {
	 $entityManager = $this-> getDoctrine($proj_id)
                -> getManager();
         $proj = $entityManager->getRepository(Projects::class)
		 -> find($proj_id);

         $users = $this->getDoctrine()
            -> getRepository(User::class)
	    -> findAll();

	 $tick = $this -> getDoctrine ($id)		     
            -> getManager()
            -> getRepository ( Ticket :: class )
            -> find ( $id );

	 $comment = new Comment();
	 $creator = $this->getUser()->getId();
//	 $comment -> setTicketId($id);
//         $comment -> setUserId(1);
         $form = $this->createForm(CommentType::class, $comment);
	 $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             $entityManager = $this->getDoctrine()->getManager();
             
             $user = $entityManager -> getRepository(User::class)->find($creator);
             $ticket = $entityManager -> getRepository(Ticket::class)->find($id);

	     $comment -> setTicket($ticket);
             $comment -> setCreator($user);

	     $entityManager -> persist($ticket);
	     $entityManager -> persist($user);
             $entityManager -> persist($comment);
             $entityManager -> flush();

             return $this->redirectToRoute('comment', ['proj_id' => $proj_id, 'id' => $id]);
         }

	 return $this->render('Ticket/newcomment.html.twig', [
	     'tick' => $tick,		 
	     'comment' => $comment,
	     'form' => $form->createView(),
	     'proj' => $proj,
         ]);
     }

     /**
      * @Route("/{proj_id}/Comment/{id}", name="comment")
      */
     public function comments($proj_id, Request $request, $id) : Response
     {
	 $entityManager = $this-> getDoctrine($proj_id)
                -> getManager();
         $proj = $entityManager->getRepository(Projects::class)
		 -> find($proj_id);

         $user = $this->getUser()->getId();
	 $tick = $this -> getDoctrine ($id)
            -> getManager()
            -> getRepository ( Ticket :: class )
	    -> find ( $id );   
//	 $ticketId = $request->query->get('id'); 
        	 
         $comments = $this->getDoctrine()
             -> getRepository(Comment::class)
             -> find($id);
 //        $user = $comments -> 
	 return $this->render('Ticket/comment.html.twig', [
		     'tick' => $tick,
		     'comment' => $comments,
		     'user' => $user,
		     'proj' => $proj,
         ]);
     }

     /**
      * @Route("/{proj_id}/{tick_id}/delete_com/{id}", name="delete_com")
      */
     public function delete_com ($proj_id, $tick_id, Request $request, $id ) : Response
     {
	  $entityManager = $this-> getDoctrine($proj_id)
                -> getManager();
          $proj = $entityManager->getRepository(Projects::class)
		 -> find($proj_id);

	  $tick = $this -> getDoctrine ($tick_id)
            -> getManager()
            -> getRepository ( Ticket :: class )
            -> find ( $tick_id );
	  //         $ticketId = $request->query->get('id');
	 $comments = $this->getDoctrine()
             -> getRepository(Comment::class)
             -> find($tick_id);

         $comment = $this -> getDoctrine ($id)
            -> getRepository ( Comment :: class )
            -> find ( $id );
	  
	 if($comment){ 
	    $em = $this->getDoctrine()->getManager();
            $em -> remove($comment);
            $em -> flush();
         
            return $this->redirectToRoute('comment', ['proj_id' => $proj_id, 'id' => $tick_id]);
         }

	  return $this->render('Ticket/comment.html.twig', [
                     'tick' => $tick,
		     'comment' => $comments,
		     'proj' => $proj,
     		    ]);
 
      }
}

