<?php

namespace App\Repository;

use App\Entity\EntityInAggregates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EntityInAggregates|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityInAggregates|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityInAggregates[]    findAll()
 * @method EntityInAggregates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityInAggregatesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EntityInAggregates::class);
    }

    // /**
    //  * @return EntityInAggregates[] Returns an array of EntityInAggregates objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EntityInAggregates
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
