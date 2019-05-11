<?php


namespace App\Controller;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(PostRepository $postRepository){
        $this->getUser();

        return $this->render('base.html.twig', [
            'data' => $postRepository->getAPI(),
            'forum' => $postRepository->getAllForumsAccueil(),
        ]);

    }
}