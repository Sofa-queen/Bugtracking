<?php

namespace App\Form\Type ;

use App\Entity\Type\Tick ;
use App\Entity\User;
use Symfony\Component\Form\AbstractType ;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\Form\FormBuilderInterface ;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType ;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TickType extends AbstractType
{
    public function buildForm ( FormBuilderInterface $builder , array $options )
    {
        $builder
	    -> add ( 'name' , TextType :: class)
	    -> add ( 'type' , ChoiceType :: class, [
		    'choices' => [
                      'Bug'  => 'bug',
		      'Task' => 'task',
                    ],
//		    'expanded' => true,
//		    'multiple' => false,
	    ]) 
//	    -> add ( 'status' , TextType :: class)
            -> add('status', ChoiceType::class, [
                'choices' => [
                        'New' => 'new',
                        'In progress' => 'in progress',
                        'Testing' => 'testing',
                        'Done' => 'done',
                ],
//                'expanded' => true,
//                'multiple' => false
            ])
            -> add('file', FileType::class, array('label' => 'Brochure (PDF file)', 'required' => false)) 
	    -> add ( 'addressee' , EntityType :: class, [ 
		    'class' => User::class,
                    'choice_label' => 'username',
            ])
	    -> add ( 'description' , TextType :: class)
	    -> add('tags_string', TextType :: class, ['mapped' => false, 'required' => false])
        ;
    }

}

