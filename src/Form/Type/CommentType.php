<?php

namespace App\Form\Type ;

use App\Entity\Type\Comment ;
use Symfony\Component\Form\AbstractType ;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\Form\FormBuilderInterface ;

class CommentType extends AbstractType
{
    public function buildForm ( FormBuilderInterface $builder , array $options )
    {
        $builder
            -> add ( 'text' , TextType :: class)
        ;
    }
}

