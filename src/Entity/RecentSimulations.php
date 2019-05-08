<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecentSimulationsRepository")
 * @ORM\Table(name="recentsimulations")
 */
class RecentSimulations
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="recentsimulationID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="recentSimulations")
     * @ORM\JoinColumn(name="userID", referencedColumnName="userID")
     */
    private $userID;

    /**
     * @ORM\ManyToOne(targetEntity="Groups", inversedBy="recentSimulations")
     * @ORM\JoinColumn(name="groupID", referencedColumnName="groupID")
     */
    private $groupID;

    /**
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="recentSimulations")
     * @ORM\JoinColumn(name="applicationID", referencedColumnName="applicationID")
     */
    private $applicationID;

    /**
     * @ORM\ManyToOne(targetEntity="Entity", inversedBy="recentSimulations")
     * @ORM\JoinColumn(name="entityID", referencedColumnName="entityID")
     */
    private $entityID;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="created_at")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserID(): ?Users
    {
        return $this->userID;
    }

    public function setUserID(?Users $userID): self
    {
        $this->userID = $userID;

        return $this;
    }

    public function getGroupID(): ?Groups
    {
        return $this->groupID;
    }

    public function setGroupID(?Groups $groupID): self
    {
        $this->groupID = $groupID;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
