<?php

namespace App\Repository;

use App\Entity\ExternalData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExternalData|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExternalData|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExternalData[]    findAll()
 * @method ExternalData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExternalDataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExternalData::class);
    }

    // /**
    //  * @return ExternalData[] Returns an array of ExternalData objects
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
    public function findOneBySomeField($value): ?ExternalData
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
