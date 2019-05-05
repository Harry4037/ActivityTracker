<?php

namespace App\Repository;

use App\Entity\GroupMembers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GroupMembers|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupMembers|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupMembers[]    findAll()
 * @method GroupMembers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupMembersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GroupMembers::class);
    }

    // /**
    //  * @return GroupMembers[] Returns an array of GroupMembers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupMembers
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
}
