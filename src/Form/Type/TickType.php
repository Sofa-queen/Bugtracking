<?php

namespace App\Form\Type ;

use App\Entity\Type\Tick ;
use App\Entity\User;
use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType ;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\Form\FormBuilderInterface ;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType ;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File ;
use Symfony\Component\OptionsResolver\OptionsResolver ;

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
	    -> add('brochureFilename', FileType::class, [ 
		    'label' => ' file',
		    'mapped' => false ,
		    'required' => false,
		    'constraints' => [
                       new File ([
			       'maxSize' => '1024k' ,
			       'mimeTypesMessage' => 'Please upload a valid PDF document' ,
		       ])
		     ],
	    ]) 
	    -> add ( 'addressee' , EntityType :: class, [ 
		    'class' => User::class,
                    'choice_label' => 'username',
            ])
	    -> add ( 'description' , TextType :: class)
	    -> add('tags_string', TextType :: class, ['mapped' => false])
        ;
    }

    public function configureOptions ( OptionsResolver $resolver )
    {
        $resolver -> setDefaults ([
            'data_class' => Ticket :: class ,
        ]);
    }
}

