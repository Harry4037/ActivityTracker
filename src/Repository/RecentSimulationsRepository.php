<?php

namespace App\Repository;

use App\Entity\RecentSimulations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RecentSimulations|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecentSimulations|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecentSimulations[]    findAll()
 * @method RecentSimulations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecentSimulationsRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, RecentSimulations::class);
    }

    public function getRecentSimulationsForUser($userID, $maxEntries) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT rs.*,rs.created_at as rs_created_at, e.*,g.*,a.* FROM `recentsimulations` rs
            JOIN users u on u.userID = rs.userID
            JOIN groups g on g.groupID = rs.groupID
            JOIN application a on a.applicationID = rs.applicationID
            JOIN entity e on e.entityID = rs.entityID
            WHERE rs.userID = '.$userID.'
            ORDER BY rs.created_at DESC, e.entity_name ASC limit '.$maxEntries;
//GROUP BY rs.groupID, rs.applicationID, rs.entityID, DATE(rs.created_at)';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

}
