<?php


namespace App\Controller;

use App\Form\UserFormType;
use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserFormType::class);
        $user = $this->getUser();

        $form->handleRequest($request);
        $pass = $user->getPassword();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($pass == $form->get('oldPass')->getData()) {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('newPass')->getData()
                    )
                );
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('user');
            }else {
                $form->get('oldPass')->addError(new FormError('L\'ancien mot de passe est incorrect'));
            }
        }


        return $this->render('user/user.html.twig', [
            'postForm' => $form->createView(),
        ]);
    }

}