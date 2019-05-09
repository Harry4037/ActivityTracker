<?php

namespace App\Repository;

use App\Entity\IsimulateUpdates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IsimulateUpdates|null find($id, $lockMode = null, $lockVersion = null)
 * @method IsimulateUpdates|null findOneBy(array $criteria, array $orderBy = null)
 * @method IsimulateUpdates[]    findAll()
 * @method IsimulateUpdates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IsimulateUpdatesRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, IsimulateUpdates::class);
    }

    public function getRecentUpdates() {

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT iu.* FROM `isimulateupdates` iu
            ORDER BY iu.created_at DESC limit 5';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

}
