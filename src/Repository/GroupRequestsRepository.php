<?php

namespace App\Repository;

use App\Entity\GroupRequests;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GroupRequests|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupRequests|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupRequests[]    findAll()
 * @method GroupRequests[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRequestsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GroupRequests::class);
    }

    // /**
    //  * @return GroupRequests[] Returns an array of GroupRequests objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupRequests
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
