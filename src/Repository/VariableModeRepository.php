<?php

namespace App\Repository;

use App\Entity\VariableMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VariableMode|null find($id, $lockMode = null, $lockVersion = null)
 * @method VariableMode|null findOneBy(array $criteria, array $orderBy = null)
 * @method VariableMode[]    findAll()
 * @method VariableMode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariableModeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VariableMode::class);
    }

    // /**
    //  * @return VariableMode[] Returns an array of VariableMode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VariableMode
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
