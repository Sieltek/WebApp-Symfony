<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Form\MessageFormType;
use App\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PostRepository;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class PostController extends AbstractController
{

    /**
     * @Route("/actu", name="actu")
     */
    public function index(PostRepository $postRepository) : Response
    {
        return $this->render('post/index.html.twig', [
            'data' => $postRepository->getAPI(),
        ]);
    }

    /**
     * @Route("/forums", name="forums")
     */
    public function indexForum(PostRepository $postRepository, Request $request ) : Response
    {
        $post = new Post();
        $user = $this->getUser();
        $form = $this->createForm(PostFormType::class);

        $form->handleRequest($request);


        $type = $request->query->get('type');
        $id = $request->query->get('id');
        $isAdmin = false;

        if ($user){
            $userRole = $user->getRoles();

            if ($userRole[0] == "ROLE_ADMIN")
                $isAdmin = true;
        }


        if ($type == 'deletePost' AND $isAdmin){
            $sql1 = "delete from message where message.post_id = :one";
            $params1 = array('one'=>$id);

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql1);
            $stmt->execute($params1);


            $sql2 = "delete from post where post.id = :one";
            $params2 = array('one'=>$id);

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql2);
            $stmt->execute($params2);

            dump('oui');

        }

        if ($form->isSubmitted() && $form->isValid()) {

            $date = new \DateTime('@'.strtotime('+2 hours'));

            $post->setTopic($form->get('titre')->getData());
            $post->setDescription($form->get('description')->getData());
            $post->setContenu($form->get('contenu')->getData());
            $post->setDate($date);
            $post->setUser($user);
            $post->setIsReport(false);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('forums');
        }

        return $this->render('post/indexForum.html.twig', [
            'forum' => $postRepository->getAllForums(),
            'postForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/forums/{id}", name="pageForum", requirements={"id"="\d+"} , methods={"GET","HEAD", "POST"})
     */
    public function pageForum(PostRepository $postRepository,$id, MessageRepository $messageRepository, Request $request) : Response
    {

        $idReport = $request->query->get('idReport');
        $idDelete = $request->query->get('idDelete');
        $type = $request->query->get('type');
        $user = $this->getUser();
        $isAdmin = false;
        if ($user) {
            $userId = $user->getId();
            $userRole = $user->getRoles();
            if ($userRole[0] == "ROLE_ADMIN")
                $isAdmin = true;
        }
        if ($type == 'message'){

            $sql = "update message set message.is_report = 1 where message.id = :one and message.is_report = :two";
            $params = array('one'=>$idReport, 'two'=> 0);

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute($params);

        }elseif ($type == 'post'){
            $sql = "update post set post.is_report = 1 where post.id = :one and post.is_report = :two";
            $params = array('one'=>$idReport, 'two'=> 0);

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute($params);
        }

        if ($type == 'deleteMessage' AND $isAdmin){
        $sql = "delete from message where message.post_id = :one and message.id = :two ";
        $params = array('one'=>$id, 'two'=>$idDelete);

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute($params);

        }elseif ($type == 'deleteMessage' AND $user){
            $sql = "delete from message where message.post_id = :one and message.id = :two and message.user_id = :three";
            $params = array('one'=>$id, 'two'=>$idDelete, 'three'=>$userId);

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute($params);

        }

        $form = $this->createForm(MessageFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = new Message();
            $date = new \DateTime('@'.strtotime('+2 hours'));

            $message->setDate($date);
            $message->setUser($this->getUser());
            $message->setCorps($form->get('contenu')->getData());
            $message->setPost($postRepository->findOneBy(array('id' => $id)));
            $message->setIsReport(false);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('pageForum', ['id' => $id]);
        }

        return $this->render('post/forumSample.html.twig', [
            'pageForum' => $postRepository->getInfoForums($id),
            'message' => $messageRepository->getMessage($id),
            'messageForm' => $form->createView(),
        ]);
    }



}
