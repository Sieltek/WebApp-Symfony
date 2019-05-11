<?php


namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin", methods={"GET","HEAD"})
     */
    public function index(PostRepository $PostRepository, MessageRepository $MessageRepository, Request $request)
    {
        $id = $request->query->get('id');
        $type = $request->query->get('type');

        if ($type == 'message'){

            $sql = "delete from message where message.id = :one and message.is_report = :two";
            $params = array('one'=>$id, 'two'=> 1);

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute($params);

        }elseif ($type == 'post'){
            $sql1 = "delete from message where message.post_id = :one";
            $params1 = array('one'=>$id);

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql1);
            $stmt->execute($params1);


            $sql2 = "delete from post where post.id = :one and post.is_report = 1";
            $params2 = array('one'=>$id);

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql2);
            $stmt->execute($params2);
        }

        return $this->render('ADMIN/admin.html.twig', [
            'controller_name' => 'AdminController',
            'messageReport' => $MessageRepository->getReportedMessage(),
            'postReport' => $PostRepository->getReportedPost(),
        ]);
    }
}