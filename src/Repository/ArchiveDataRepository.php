<?php

namespace App\Repository;

use App\Entity\ArchiveData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ArchiveData|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArchiveData|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArchiveData[]    findAll()
 * @method ArchiveData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchiveDataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ArchiveData::class);
    }

    // /**
    //  * @return ArchiveData[] Returns an array of ArchiveData objects
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
    public function findOneBySomeField($value): ?ArchiveData
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
