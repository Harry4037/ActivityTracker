<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApplicationRequestsRepository")
 * @ORM\Table(name="applicationrequests")
 */
class ApplicationRequests
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="applicationRequestID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="applicationRequests")
     * @ORM\JoinColumn(name="applicationID", referencedColumnName="applicationID")
     */
    private $applicationID;

    /**
     * @ORM\ManyToOne(targetEntity="Groups", inversedBy="applicationrequests")
     * @ORM\JoinColumn(name="groupID", referencedColumnName="groupID")
     */
    private $groupID;

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

    public function getGroupID(): ?Groups
    {
        return $this->groupID;
    }

    public function setGroupID(?Groups $groupID): self
    {
        $this->groupID = $groupID;

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
