<?php

namespace App\Repository;

use App\Entity\ApplicationEquations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ApplicationEquations|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApplicationEquations|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApplicationEquations[]    findAll()
 * @method ApplicationEquations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationEquationsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ApplicationEquations::class);
    }

    // /**
    //  * @return ApplicationEquations[] Returns an array of ApplicationEquations objects
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
    public function findOneBySomeField($value): ?ApplicationEquations
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
