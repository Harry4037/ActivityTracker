<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupApplicationNotificationsRepository")
 * @ORM\Table(name="groupapplicationnotifications")
 */
class GroupApplicationNotifications
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="groupapplicationnotificationID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="groupApplicationNotifications")
     * @ORM\JoinColumn(name="userID", referencedColumnName="userID")
     */
    private $userID;

    /**
     * @ORM\ManyToOne(targetEntity="Groups", inversedBy="groupApplicationNotifications")
     * @ORM\JoinColumn(name="groupID", referencedColumnName="groupID")
     */
    private $groupID;

    /**
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="groupApplicationNotifications")
     * @ORM\JoinColumn(name="applicationID", referencedColumnName="applicationID")
     */
    private $applicationID;

    /**
     * @ORM\Column(type="integer", name="isDeleted", columnDefinition="TINYINT(1) NULL")
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="integer", name="approved", columnDefinition="TINYINT(1) DEFAULT 1 NOT NULL")
     */
    private $approved;

    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=true)
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

    public function getIsDeleted(): ?int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?int $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getApproved(): ?int
    {
        return $this->approved;
    }

    public function setApproved(int $approved): self
    {
        $this->approved = $approved;

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
