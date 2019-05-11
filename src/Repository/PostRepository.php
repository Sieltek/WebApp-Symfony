<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[] Returns an array of Post objects
     */

    private static $apiKey = '10edc5183945436fbc3865e3ca1c9f9a';

    public function getAPI()
    {
        $res = '';
        $json = file_get_contents("https://newsapi.org/v2/top-headlines?sources=google-news-fr&apiKey=" . self::$apiKey);
        $json = json_decode($json);
        if ($json->status == 'ok' && $json->totalResults > 0) {
            $res = $json->articles;
        }
        return $res;
    }

    public function getAllForums()
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.date', 'DESC')
          //  ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    public function getAllForumsAccueil()
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.date', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getInfoForums($id)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getReportedPost()
    {
        return $this->createQueryBuilder('m')
            ->where('m.isReport = 1')
            ->orderBy('m.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
