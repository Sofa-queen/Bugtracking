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
      * @Route("/new_comment/{id}", name="newcomment")
      */
     public function newcomment ( Request $request, $id ) : Response
     {
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

             return $this->redirectToRoute('comment', ['id' => $id]);
         }

	 return $this->render('Ticket/newcomment.html.twig', [
	     'tick' => $tick,		 
	     'comment' => $comment,
             'form' => $form->createView(),
         ]);
     }

     /**
      * @Route("/Comment/{id}", name="comment")
      */
     public function comments( Request $request, $id) : Response
     {
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
         ]);
     }

     /**
      * @Route("/{tick_id}/delete_com/{id}", name="delete_com")
      */
     public function delete_com ($tick_id, Request $request, $id ) : Response
     {
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
         
            return $this->redirectToRoute('comment', ['id' => $tick_id]);
         }

	  return $this->render('Ticket/comment.html.twig', [
                     'tick' => $tick,
                     'comment' => $comments,
         ]);
 
      }
}

