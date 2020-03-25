<?php

namespace App\Form\Type ;

use App\Entity\Type\Proj ;
use Symfony\Component\Form\AbstractType ;
use Symfony\Component\Form\Extension\Core\Type\DateType ;
use Symfony\Component\Form\Extension\Core\Type\SubmitType ;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\Form\FormBuilderInterface ;

class ProjType extends AbstractType
{
    public function buildForm ( FormBuilderInterface $builder , array $options )
    {
        $builder
            -> add ( 'name_proj' , TextType :: class)
           // -> add ( 'dueDate' , DateType :: class )
           // -> add ( 'save' , SubmitType :: class )
        ;
    }
}
