<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntityInApplicationRepository")
 * @ORM\Table(name="entitiesinapplications")
 */
class EntityInApplication
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="entityapplicationID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="entityInApplications")
     * @ORM\JoinColumn(name="applicationID", referencedColumnName="applicationID")
     */
    private $applicationID;

    /**
     * @ORM\ManyToOne(targetEntity="Entity", inversedBy="entityInApplications", fetch="EAGER")
     * @ORM\JoinColumn(name="entityID", referencedColumnName="entityID")
     */
    private $entityID;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApplicationID(): ?Application
    {
        return $this->applicationID;
    }

    public function setApplicationID(?Application $applicationID): self
    {
        $this->applicationID = $applicationID;

        return $this;
    }

    public function getEntityID(): ?Entity
    {
        return $this->entityID;
    }

    public function setEntityID(?Entity $entityID): self
    {
        $this->entityID = $entityID;

        return $this;
    }
}
