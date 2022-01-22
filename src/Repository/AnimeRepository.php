<?php

namespace App\Repository;

use App\Entity\Anime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Anime|null find($id, $lockMode = null, $lockVersion = null)
 * @method Anime|null findOneBy(array $criteria, array $orderBy = null)
 * @method Anime[]    findAll()
 * @method Anime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Anime::class);
    }

    public function getAnimesByTitleJS(string $title)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT a_id, a_title, a_aired, a_status, a_score, a_cover FROM ms_anime WHERE a_title LIKE :title LIMIT 10';

        $stmt = $conn->prepare($sql);
        $res = $stmt->executeQuery(['title' => '%'.$title.'%']);

        return $res->fetchAllAssociative();
    }

    public function getAnimesByTitle(string $title)
    {
        return $this->getEntityManager()->createQuery('
            SELECT a
            FROM App\Entity\Anime a
            WHERE a.title LIKE :title
        ')->setParameter('title', '%'.$title.'%')->getResult();
    }

    public function getAnimesBySeason(string $season)
    {

        return $this->getEntityManager()->createQuery('
            SELECT a
            FROM App\Entity\Anime a
            WHERE a.premiered = :premiered
        ')->setParameter('premiered', $season)->getResult();
    }

    public function getTopAnimes(string $orderBy, int $limit, string $condition = '1 = 1')
    {
        return $this->getEntityManager()->createQuery('
            SELECT a.id, a.title, a.cover, t.name, a.episodes, a.score, a.members 
            FROM App\Entity\Anime a
            INNER JOIN a.type t
            WHERE '.$condition.'
            ORDER BY '.$orderBy.' DESC')->setMaxResults($limit)->getResult();
    }

    // /**
    //  * @return Anime[] Returns an array of Anime objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Anime
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
