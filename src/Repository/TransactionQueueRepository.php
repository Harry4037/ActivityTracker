<?php

namespace App\Repository;

use App\Entity\TransactionQueue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TransactionQueue|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionQueue|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionQueue[]    findAll()
 * @method TransactionQueue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionQueueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TransactionQueue::class);
    }

    // /**
    //  * @return TransactionQueue[] Returns an array of TransactionQueue objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TransactionQueue
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
