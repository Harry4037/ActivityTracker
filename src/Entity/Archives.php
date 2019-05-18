<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArchivesRepository")
 * @ORM\Table(name="archives")
 */
class Archives
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="archiveID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="UserTransactions", inversedBy="archives")
     * @ORM\JoinColumn(name="relatedTransactionID", referencedColumnName="transactionID")
     */
    private $relatedTransactionID;

    /**
     * @ORM\ManyToOne(targetEntity="Groups", inversedBy="archives")
     * @ORM\JoinColumn(name="groupID", referencedColumnName="groupID")
     */
    private $groupID;

    /**
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="archives")
     * @ORM\JoinColumn(name="applicationID", referencedColumnName="applicationID")
     */
    private $applicationID;

    /**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="archives")
     * @ORM\JoinColumn(name="userID", referencedColumnName="userID")
     */
    private $userID;

    /**
     * @ORM\ManyToOne(targetEntity="Entity", inversedBy="archives")
     * @ORM\JoinColumn(name="entityID", referencedColumnName="entityID")
     */
    private $entityID;

    /**
     * @ORM\ManyToOne(targetEntity="Command", inversedBy="archives")
     * @ORM\JoinColumn(name="commandID", referencedColumnName="commandID")
     */
    private $commandID;

    /**
     * @ORM\Column(type="integer", nullable=true, name="error")
     */
    private $error;

    /**
     * @ORM\Column(type="string", length=300, nullable=true, name="comment")
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="created_at")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArchiveData", mappedBy="archiveID")
     */
    private $archiveData;

    public function __construct()
    {
        $this->archiveData = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelatedTransactionID(): ?UserTransactions
    {
        return $this->relatedTransactionID;
    }

    public function setRelatedTransactionID(?UserTransactions $relatedTransactionID): self
    {
        $this->relatedTransactionID = $relatedTransactionID;

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

    /**
     * @return Collection|ArchiveData[]
     */
    public function getArchiveData(): Collection
    {
        return $this->archiveData;
    }

    public function addArchiveData(ArchiveData $archiveData): self
    {
        if (!$this->archiveData->contains($archiveData)) {
            $this->archiveData[] = $archiveData;
            $archiveData->setArchiveID($this);
        }

        return $this;
    }

    public function removeArchiveData(ArchiveData $archiveData): self
    {
        if ($this->archiveData->contains($archiveData)) {
            $this->archiveData->removeElement($archiveData);
            // set the owning side to null (unless already changed)
            if ($archiveData->getArchiveID() === $this) {
                $archiveData->setArchiveID(null);
            }
        }

        return $this;
    }
}
