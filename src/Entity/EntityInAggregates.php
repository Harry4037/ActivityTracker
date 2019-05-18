<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntityInAggregatesRepository")
 * @ORM\Table(name="entityinaggregates")
 */
class EntityInAggregates
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="entityinaggregateID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="entityInAggregates")
     * @ORM\JoinColumn(name="applicationID", referencedColumnName="applicationID")
     */
    private $applicationID;

    /**
     * @ORM\ManyToOne(targetEntity="Entity", inversedBy="entityInAggregates")
     * @ORM\JoinColumn(name="aggregateID", referencedColumnName="entityID")
     */
    private $aggregateID;

    /**
     * @ORM\ManyToOne(targetEntity="Entity", inversedBy="entityInAggregates")
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

    public function getAggregateID(): ?Entity
    {
        return $this->aggregateID;
    }

    public function setAggregateID(?Entity $aggregateID): self
    {
        $this->aggregateID = $aggregateID;

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
