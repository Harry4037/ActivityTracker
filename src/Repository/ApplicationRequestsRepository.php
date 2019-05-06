<?php

namespace App\Repository;

use App\Entity\ApplicationRequests;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ApplicationRequests|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApplicationRequests|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApplicationRequests[]    findAll()
 * @method ApplicationRequests[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationRequestsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ApplicationRequests::class);
    }

    // /**
    //  * @return ApplicationRequests[] Returns an array of ApplicationRequests objects
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
    public function findOneBySomeField($value): ?ApplicationRequests
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
