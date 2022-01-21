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

    public function emailExists(string $email): bool
    {
        return (bool) $this->getEntityManager()->createQuery('
            SELECT u
            FROM App\Entity\User u
            WHERE u.email = :email
        ')->setParameter('email', $email)->getResult();
    }

    public function exists(string $username): bool
    {
        return (bool) $this->getEntityManager()->createQuery('
            SELECT u
            FROM App\Entity\User u
            WHERE u.username = :username
        ')->setParameter('username', $username)->getResult();
    }

    public function getProfileStats(string $username)
    {
        // return $this->getEntityManager()->createQuery('
        //     SELECT lt.name, COUNT()
        //     FROM App\Entity\ListType lt
        //     LEFT JOIN (SELECT * FROM

        //     SELECT list, COUNT(user_id) AS total 
        //     FROM lists LEFT JOIN (SELECT * FROM users_lists, users WHERE user_id = users.id AND username = ?) ul ON lists.id = ul.list_id 
        //     GROUP BY list
        // ')

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT lt_name, COUNT(ul_user_id) AS total FROM ms_list_type LEFT JOIN (SELECT * FROM ms_user_list, ms_user WHERE ul_user_id = u_id AND u_username = :username) ul ON lt_id = ul.ul_list_type_id GROUP BY lt_name';

        $stmt = $conn->prepare($sql);
        $res = $stmt->executeQuery(['username' => $username]);
        $res = $res->fetchAllAssociative();

        $arr = [];

        foreach($res as $elmt)
        {
            $arr[$elmt['lt_name']] = $elmt['total'];
        }

        return $arr;
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
