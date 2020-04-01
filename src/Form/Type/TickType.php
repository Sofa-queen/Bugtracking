<?php

namespace App\Form\Type ;

use App\Entity\Type\Tick ;
use Symfony\Component\Form\AbstractType ;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\Form\FormBuilderInterface ;


class TickType extends AbstractType
{
    public function buildForm ( FormBuilderInterface $builder , array $options )
    {
        $builder
	    -> add ( 'name' , TextType :: class)
            -> add ( 'type' , TextType :: class)
	    -> add ( 'status' , TextType :: class)
	    -> add ( 'addressee' , TextType :: class)
//	    -> add ( 'description' , TextType :: class)
        ;
    }
}

