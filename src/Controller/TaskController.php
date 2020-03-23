<?php
namespace App\Controller ;

use App\Entity\Task ;
use App\Form\Type\TaskType ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController ;
use Symfony\Component\HttpFoundation\Request ;

class TaskController extends AbstractController
{
	 /**
     * @Route("/task")
     */

    public function new ( Request $request )
    {
        $task = new Task ();
        $task->setTask('Write a blog post');
	$task->setDueDate(new \DateTime('tomorrow'));

	$form = $this -> createForm ( TaskType :: class , $task );

	$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
  //          return $this->redirectToRoute('task_success');
        }


        return $this -> render ( 'task/new.html.twig' , [
            'form' => $form -> createView (),
        ]);
    }
}
