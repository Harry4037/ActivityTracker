<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupMembersRepository")
 * @ORM\Table(name="groupmembers")
 */
class GroupMembers
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer",name="groupMemberID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Groups", inversedBy="groupMembers", fetch="EAGER")
     * @ORM\JoinColumn(name="groupID", referencedColumnName="groupID")
     */
    private $groupID;

    /**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="groupMembers")
     * @ORM\JoinColumn(name="userID", referencedColumnName="userID")
     */
    private $userID;

    /**
     * @ORM\Column(type="integer", nullable=true,name="admin", options={"default":"0"})
     */
    private $admin;

    /**
     * @ORM\Column(type="integer", nullable=true, name="permissionLevel", columnDefinition="TINYINT DEFAULT 23")
     */
    private $permissionLevel;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="created_at")
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

    public function getAdmin(): ?int
    {
        return $this->admin;
    }

    public function setAdmin(?int $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getPermissionLevel(): ?int
    {
        return $this->permissionLevel;
    }

    public function setPermissionLevel(?int $permissionLevel): self
    {
        $this->permissionLevel = $permissionLevel;

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
