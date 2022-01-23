<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function getProfileStats(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT lt_name, COUNT(ul_user_id) AS total 
                FROM ms_list_type LEFT JOIN 
                    (SELECT * FROM ms_user_list WHERE ul_user_id = :id) ul 
                ON lt_id = ul_list_type_id 
                GROUP BY lt_name';

        $stmt = $conn->prepare($sql);
        
        $res = $stmt->executeQuery(['id' => $id]);
        $res = $res->fetchAllAssociative();

        $stats = [];

        foreach($res as $val)
        {
            $stats[$val['lt_name']] = $val['total'];
        }

        return $stats;
    }

    public function getProfileTotalEpisodes(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT SUM(a_episodes) AS total_episodes
                FROM ms_anime, ms_user_list, ms_list_type
                WHERE a_id = ul_anime_id AND ul_list_type_id = lt_id AND lt_name = "Completed" AND ul_user_id = :id';

        $stmt = $conn->prepare($sql);
        $res = $stmt->executeQuery(['id' => $id]);

        return $res->fetchAssociative()['total_episodes'];
    }

    // /**
    //  * @return User[] Returns an array of User objects
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
    public function findOneBySomeField($value): ?User
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
