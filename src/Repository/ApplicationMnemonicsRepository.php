<?php

namespace App\Repository;

use App\Entity\ApplicationMnemonics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ApplicationMnemonics|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApplicationMnemonics|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApplicationMnemonics[]    findAll()
 * @method ApplicationMnemonics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationMnemonicsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ApplicationMnemonics::class);
    }

    // /**
    //  * @return ApplicationMnemonics[] Returns an array of ApplicationMnemonics objects
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
    public function findOneBySomeField($value): ?ApplicationMnemonics
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
