<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserTransactionsRepository")
 * @ORM\Table(name="usertransactions")
 */
class UserTransactions
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="usertransactionID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Groups", inversedBy="userTransactions")
     * @ORM\JoinColumn(name="groupID", referencedColumnName="groupID")
     */
    private $groupID;

    /**
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="userTransactions")
     * @ORM\JoinColumn(name="applicationID", referencedColumnName="applicationID")
     */
    private $applicationID;

    /**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="userTransactions")
     * @ORM\JoinColumn(name="userID", referencedColumnName="userID")
     */
    private $userID;

    /**
     * @ORM\ManyToOne(targetEntity="Entity", inversedBy="userTransactions")
     * @ORM\JoinColumn(name="entityID", referencedColumnName="entityID")
     */
    private $entityID;

    /**
     * @ORM\ManyToOne(targetEntity="Command", inversedBy="userTransactions", fetch="EAGER")
     * @ORM\JoinColumn(name="commandID", referencedColumnName="commandID")
     */
    private $commandID;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $error;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $comment;

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

    public function getApplicationID(): ?Application
    {
        return $this->applicationID;
    }

    public function setApplicationID(?Application $applicationID): self
    {
        $this->applicationID = $applicationID;

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

    public function getEntityID(): ?Entity
    {
        return $this->entityID;
    }

    public function setEntityID(?Entity $entityID): self
    {
        $this->entityID = $entityID;

        return $this;
    }

    public function getCommandID(): ?Command
    {
        return $this->commandID;
    }

    public function setCommandID(?Command $commandID): self
    {
        $this->commandID = $commandID;

        return $this;
    }

    public function getError(): ?int
    {
        return $this->error;
    }

    public function setError(?int $error): self
    {
        $this->error = $error;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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
