<?php

namespace App\Repository;

use App\Entity\GroupMembershipNotifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GroupMembershipNotifications|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupMembershipNotifications|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupMembershipNotifications[]    findAll()
 * @method GroupMembershipNotifications[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupMembershipNotificationsRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, GroupMembershipNotifications::class);
    }

    public function getNotificationsForUser($userID) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT gmn.*,g.* FROM `groupmembershipnotifications` gmn
            JOIN groups g on g.groupID = gmn.groupID
            WHERE gmn.userID = '.$userID;
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

}
