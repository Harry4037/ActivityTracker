<?php

namespace App\Repository;

use App\Entity\GroupApplicationNotifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GroupApplicationNotifications|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupApplicationNotifications|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupApplicationNotifications[]    findAll()
 * @method GroupApplicationNotifications[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupApplicationNotificationsRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, GroupApplicationNotifications::class);
    }

    public function getNotificationsForGroupsUserIsMemberOf($userID) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT gan.*, gm.*,g.*,a.*, u.* FROM `groupapplicationnotifications` gan
            JOIN groupmembers gm on gm.groupID = gan.groupID AND gm.userID = gan.userID
            JOIN groups g on g.groupID = gan.groupID
            JOIN application a on a.applicationID = gan.applicationID
            JOIN users u on u.userID = gan.userID
            WHERE gm.userID = '.$userID;
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

}
