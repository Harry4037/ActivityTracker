<?php

namespace App\Repository;

use App\Entity\CurrentTransactions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CurrentTransactions|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrentTransactions|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrentTransactions[]    findAll()
 * @method CurrentTransactions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrentTransactionsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CurrentTransactions::class);
    }

    // /**
    //  * @return CurrentTransactions[] Returns an array of CurrentTransactions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CurrentTransactions
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
