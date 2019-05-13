<?php

namespace App\Repository;

use App\Entity\EntityInApplication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EntityInApplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityInApplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityInApplication[]    findAll()
 * @method EntityInApplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityInApplicationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EntityInApplication::class);
    }

    // /**
    //  * @return EntityInApplication[] Returns an array of EntityInApplication objects
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
    public function findOneBySomeField($value): ?EntityInApplication
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
