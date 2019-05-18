<?php

namespace App\Repository;

use App\Entity\SecurityQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SecurityQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecurityQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SecurityQuestion[]    findAll()
 * @method SecurityQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecurityQuestionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SecurityQuestion::class);
    }

    // /**
    //  * @return SecurityQuestion[] Returns an array of SecurityQuestion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SecurityQuestion
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
