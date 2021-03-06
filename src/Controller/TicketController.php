<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Ticket;
use App\Entity\User ;
use App\Entity\Projects ;
use App\Entity\Comment;
use App\Form\Type\CommentType ;
use App\Form\Type\TickType ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface ;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class TicketController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/newTick/{proj_id}", name="creat_ticket", methods={"GET","POST"})
     */
    public function new ( $proj_id, Request $request, SluggerInterface $slugger ) : Response
    {
        $users = $this-> getDoctrine()
            -> getRepository(User::class)
	    -> findAll();

	$entityManager = $this-> getDoctrine($proj_id)
		-> getManager();
        $project = $entityManager-> getRepository(Projects::class)
		-> find($proj_id);

	$ticket = new Ticket ();
        $creator = $this-> getUser()-> getId();
        $form = $this-> createForm ( TickType :: class , $ticket );
     	$form->handleRequest($request);

        if ($form-> isSubmitted() && $form-> isValid()) {
	    $em = $this-> getDoctrine()-> getManager();

	    $user = $em-> getRepository(User::class)-> find($creator);
            $project = $em-> getRepository(Projects::class)-> find($proj_id);

	    $ticket-> setCreator($user);
            $ticket-> setProject($project);

            $em -> persist($project);
            $em -> persist($ticket);
	    $em -> persist($user);

	    $tagsString = $request-> request-> get('tick')['tags_string'];
	    $tags = array_map(function($value){ return trim($value); }, explode(',', $tagsString));
   
	    foreach ($tags as $tagName) {
		$tag = new Tag();
                $has_tag = $em-> getRepository(Tag::class)-> findOneBy(
                    ['name' => $tagName]
                );
                if ($has_tag) {
                    $ticket-> addTag($has_tag);
		} else { 
          	    $tag-> setName($tagName);
	            $em-> persist($tag);
		    $ticket-> addTag($tag);
	        }        
             }

	    /** @var UploadedFile $brochureFile */

            $brochureFile = $form->get('brochureFilename')->getData();
            if ($brochureFile) {
		    $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                    try {
                       $brochureFile->move(
                          $this->getParameter('files_directory'),
                          $newFilename
                       );
		    } 
		    catch (FileException $e) {
	                throw $this->createNotFoundException(
                           'File does not upload' . $e
                        );
		    }
		    $ticket->setBrochureFilename($newFilename);
	    }

            $em -> flush();

          return $this-> redirectToRoute('show_project', ['id' => $proj_id]);
        }


	return $this-> render ( 'Ticket/ticknew.html.twig' , [
		'proj' => $project,
		'ticket' => $ticket,      
		'users' => $users,
		'form' => $form -> createView (),
        ]);
    }

    /**
      * @IsGranted("ROLE_USER")
      * @Route("/{project_id}/editTick/{id}", name="edit_ticket",  methods={"GET","POST"})
      */
     public function edit (  Request $request, $id, $project_id, SluggerInterface $slugger ) : Response
     {
         $entityManager = $this-> getDoctrine($project_id)
                -> getManager();
         $project = $entityManager->getRepository(Projects::class)
		 -> find($project_id);

         $tick = $this-> getDoctrine ($id)
            -> getRepository ( Ticket :: class )
            -> find ( $id );

         if ( ! $tick ) {
            throw $this-> createNotFoundException (
               'No ticket found for id ' . $id
             );
         }

	 $tick-> getName (); 
         $form = $this-> createForm ( TickType :: class , $tick );
	 $form->handleRequest($request);

         $tags_string = $request-> request-> get('tick')['tags_string'];
         $tags = array_map(function ($value) { return trim($value); }, explode(',', $tags_string));

	 if ($form-> isSubmitted() && $form-> isValid()) {

            $em = $this-> getDoctrine()-> getManager();
	    $em-> persist($tick);

            foreach($tick-> getTags() as $tag) {
                $tick-> removeTag($tag);
            }
            foreach ($tags as $tagName) {                
                $tag = new Tag();
                $has_tag = $em-> getRepository(Tag::class)-> findOneBy(
                    ['name' => $tagName]
                );
                if ($has_tag) {
                    $tick-> addTag($has_tag);
                } else {

                    $tag-> setName($tagName);
                    $em-> persist($tag);
                    $tick-> addTag($tag);
                }
	    }

	    /** @var UploadedFile $brochureFile */

            $brochureFile = $form->get('brochureFilename')->getData();
            if ($brochureFile) {
                    $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                    try {
                       $brochureFile->move(
                          $this->getParameter('files_directory'),
                          $newFilename
                       );
                    }
                    catch (FileException $e) {
                        throw $this->createNotFoundException(
                           'File does not upload' . $e
                        );
                    }
                    $tick->setBrochureFilename($newFilename);
            }

            $em-> flush();
            return $this-> redirectToRoute('show_project', ['id' => $project_id]);
         }


         return $this -> render ( 'Ticket/edit.html.twig' , [
                'form' => $form -> createView (), 'name' => $tick -> getName () , 'proj' => $project,
           ]);
     }

     /**
      * @IsGranted("ROLE_USER")
      * @Route("/{project_id}/delete_ticket/{id}", name="delete_ticket")
      */
     public function delete_tick ( Request $request, $id, $project_id ) : Response
     {
         $projectId = $request-> query-> get('project_id');
         $tick = $this -> getDoctrine ($id)
            -> getRepository ( Ticket :: class )
	    -> find ( $id );

         if (!$tick) {
		    throw $this->createNotFoundException(
			    'No ticket found for id '.$id
                );
         }
         $em = $this-> getDoctrine()-> getManager();  
	 $em -> remove($tick);
         $em -> flush();


         return $this-> redirectToRoute('show_project', ['id' => $project_id]);
      }

     /**
      * @IsGranted("ROLE_USER")
      * @Route("/{proj_id}/Tick/{id}", name="ticket")
      */
     public function ticket ( $proj_id, Request $request, $id ) : Response
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

         if ( ! $tick ) {
            throw $this -> createNotFoundException (
               'No project found for id ' . $id
            );
	 }
         
         $comment = new Comment();
         $creator = $this->getUser()->getId();
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

	     return $this->redirectToRoute('ticket', ['proj_id' => $proj_id, 'id' => $id]);
         }


	 return $this->render('Ticket/tick.html.twig', [		  
		  'tick' => $tick,
		  'proj' => $proj,
		  'comment' => $comment,
                  'form' => $form->createView(),
	  ]);
     }

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

         $comments = $this->getDoctrine()
            -> getRepository(Comment::class)
            -> find($id);

         return $this->render('Ticket/tick.html.twig', [
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

            return $this->redirectToRoute('ticket', ['proj_id' => $proj_id, 'id' => $tick_id]);
         }

          return $this->render('Ticket/tick.html.twig', [
                     'tick' => $tick,
                     'comment' => $comments,
                     'proj' => $proj,
                    ]);

      }
}
