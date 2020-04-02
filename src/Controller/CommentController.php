<?php

namespace App\Controller;

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
	 $tick = $this -> getDoctrine ($id)
            -> getManager()
            -> getRepository ( Ticket :: class )
            -> find ( $id );

//         $ticketId = $request->query->get('id');
	 $comment = new Comment();
	 $comment -> setTicketId($id);
	 $comment -> setUserId(1);
         $form = $this->createForm(CommentType::class, $comment);
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             $entityManager = $this->getDoctrine()->getManager();
//             $ticket = $entityManager->getRepository(Ticket::class)->find($ticketId);

//             $comment->setProject($tick);

//             $entityManager->persist($tick);
             $entityManager->persist($comment);
             $entityManager->flush();

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
	 $tick = $this -> getDoctrine ($id)
            -> getManager()
            -> getRepository ( Ticket :: class )
	    -> find ( $id );   
//	 $ticketId = $request->query->get('id');    
         $comments = $this->getDoctrine()
             ->getRepository(Comment::class)
             ->findAll();

	 return $this->render('Ticket/comment.html.twig', [
		     'tick' => $tick,
		     'comment' => $comments,
         ]);
     }
}

