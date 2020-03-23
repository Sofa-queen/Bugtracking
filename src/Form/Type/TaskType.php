<?php
 // src/Form/Type/TaskType.php
namespace App\Form\Type ;

use Symfony\Component\Form\AbstractType ;
use Symfony\Component\Form\Extension\Core\Type\DateType ;
use Symfony\Component\Form\Extension\Core\Type\SubmitType ;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\Form\FormBuilderInterface ;

class TaskType extends AbstractType
{
    public function buildForm ( FormBuilderInterface $builder , array $options )
    {
        $builder
            -> add ( 'task' , TextType :: class )
            -> add ( 'dueDate' , DateType :: class )
	    //  -> add ( 'save' , SubmitType :: class )
	    -> add('save', SubmitType::class, array('label' => 'Create Task'))
	    -> getForm();
    }
}
