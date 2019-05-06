<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApplicationAdminsRepository")
 * @ORM\Table(name="applicationadmins")
 */
class ApplicationAdmins
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="applicationAdminID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="applicationAdmins")
     * @ORM\JoinColumn(name="applicationID", referencedColumnName="applicationID")
     */
    private $applicationID;

    /**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="applicationAdmins")
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
