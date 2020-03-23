<?php
namespace App\Controller ;

use App\Entity\Projects ;
use Doctrine\ORM\EntityManagerInterface ;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProjectsController extends AbstractController
{
    /**
     * @Route("/projects", name="create_project")
     */
    public function createProject () : Response
    {
        $entityManager = $this -> getDoctrine () -> getManager ();

        $proj = new Projects ();
        $proj -> setNameProj ( 'Keyboard' );
        $proj -> setCreator ( 1999 );

        $entityManager -> persist ( $proj );

        $entityManager -> flush ();

        return new Response ( 'Saved new project with id ' . $proj -> getId ());
    }
}
