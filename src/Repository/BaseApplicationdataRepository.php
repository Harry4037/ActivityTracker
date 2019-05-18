<?php

namespace App\Repository;

use App\Entity\BaseApplicationdata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BaseApplicationdata|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseApplicationdata|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseApplicationdata[]    findAll()
 * @method BaseApplicationdata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseApplicationdataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BaseApplicationdata::class);
    }

    // /**
    //  * @return BaseApplicationdata[] Returns an array of BaseApplicationdata objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BaseApplicationdata
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
