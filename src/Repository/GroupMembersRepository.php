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
class GroupMembersRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, GroupMembers::class);
    }

    public function retrieveGroupMember($userID, $groupID) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT gm.* FROM `groupmembers` gm WHERE gm.groupID = ' . $groupID . ' and gm.userID = ' . $userID;
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetch();
    }

}
