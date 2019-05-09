<?php

namespace App\Repository;

use App\Entity\GroupApplications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GroupApplications|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupApplications|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupApplications[]    findAll()
 * @method GroupApplications[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupApplicationsRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, GroupApplications::class);
    }

    public function getApplicationSubscriptions($groupID) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT ga.*,a.* FROM `groupapplications` ga
            JOIN application a on a.applicationID = ga.applicationID
            WHERE ga.groupID = ' . $groupID;
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

}
