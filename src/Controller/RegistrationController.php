<?php
// src/Controller/RegistrationController.php
namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController ;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request , UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) постройте форму
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) обработка отправки
        $form->handleRequest($request);
//	echo "<pre>";
//	var_dump($request); die;
        if ($form->isSubmitted() && $form->isValid()) {
          
            // 3) шифрование пароль
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
	    var_dump($user);
	    die;
            // 4) сохранение пользователя
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('replace_with_some_route');
        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }
}
