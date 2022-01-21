<?php

namespace App\Repository;

use App\Entity\UserList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserList|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserList|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserList[]    findAll()
 * @method UserList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserList::class);
    }

    public function getListOf(string $username, int $animeId)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT lt_list_key, ul_progress_episodes, ul_score FROM ms_user, ms_user_list, ms_list_type WHERE u_username = :username AND u_id = ul_user_id AND lt_id = ul_list_type_id AND ul_anime_id = :anime';
        
        $stmt = $conn->prepare($sql);
        $res = $stmt->executeQuery(['username' => $username, 'anime' =>  $animeId]);

        return $res->fetchAllAssociative();
    }

    // /**
    //  * @return UserList[] Returns an array of UserList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserList
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
