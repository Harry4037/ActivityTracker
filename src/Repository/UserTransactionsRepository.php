<?php

namespace App\Repository;

use App\Entity\UserTransactions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserTransactions|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTransactions|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTransactions[]    findAll()
 * @method UserTransactions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTransactionsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserTransactions::class);
    }

    // /**
    //  * @return UserTransactions[] Returns an array of UserTransactions objects
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
    public function findOneBySomeField($value): ?UserTransactions
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
