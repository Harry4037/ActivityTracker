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
class ApplicationAdminsRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, ApplicationAdmins::class);
    }

    public function getApplicationsUserIsAnAdminOf($userID) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT aa.*, a.* FROM `applicationadmins` aa
            JOIN application a on a.applicationID = aa.applicationID
            WHERE aa.userID = ' . $userID . '
            ORDER BY a.application_name ASC';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

}
