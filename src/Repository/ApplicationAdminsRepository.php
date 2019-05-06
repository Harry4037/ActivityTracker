<?php

namespace App\Repository;

use App\Entity\ApplicationAdmins;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ApplicationAdmins|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApplicationAdmins|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApplicationAdmins[]    findAll()
 * @method ApplicationAdmins[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationAdminsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ApplicationAdmins::class);
    }

    // /**
    //  * @return ApplicationAdmins[] Returns an array of ApplicationAdmins objects
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
    public function findOneBySomeField($value): ?ApplicationAdmins
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
