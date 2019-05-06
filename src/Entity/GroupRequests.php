<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupRequestsRepository")
 * @ORM\Table(name="grouprequests")
 */
class GroupRequests
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Groups", inversedBy="groupRequests")
     * @ORM\JoinColumn(name="groupID", referencedColumnName="groupID")
     */
    private $groupID;

    /**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="groupRequests")
     * @ORM\JoinColumn(name="userID", referencedColumnName="userID")
     */
    private $userID;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserID(): ?Users
    {
        return $this->userID;
    }

    public function setUserID(?Users $userID): self
    {
        $this->userID = $userID;

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
