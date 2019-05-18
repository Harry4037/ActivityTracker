<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="integer", name="transactionID")
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
     * @ORM\OneToMany(targetEntity="TransactionQueue", mappedBy="transactionID")
     */
    private $transactionQueues;
    
    /**
     * @ORM\OneToMany(targetEntity="TransactionData", mappedBy="transactionID")
     */
    private $transactionData;

    
    /**
     * @ORM\OneToMany(targetEntity="Archives", mappedBy="relatedTransactionID")
     */
    private $archives;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CurrentTransactions", mappedBy="transactionID")
     */
    private $currentTransactions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DisabledTransactionNotifications", mappedBy="transactionID")
     */
    private $disabledTransactionNotifications;

    public function __construct()
    {
        $this->currentTransactions = new ArrayCollection();
        $this->disabledTransactionNotifications = new ArrayCollection();
    }

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
    
    /**
     * @return Collection|TransactionQueue[]
     */
    public function getTransactionQueues(): Collection
    {
        return $this->transactionQueues;
    }

    public function addTransactionQueue(TransactionQueue $transactionQueue): self
    {
        if (!$this->transactionQueues->contains($transactionQueue)) {
            $this->transactionQueues[] = $transactionQueue;
            $transactionQueue->setTransactionID($this);
        }

        return $this;
    }

    public function removeTransactionQueue(TransactionQueue $transactionQueue): self
    {
        if ($this->transactionQueues->contains($transactionQueue)) {
            $this->transactionQueues->removeElement($transactionQueue);
            // set the owning side to null (unless already changed)
            if ($transactionQueue->getTransactionID() === $this) {
                $transactionQueue->setTransactionID(null);
            }
        }

        return $this;
    }
    
    /**
     * @return Collection|TransactionData[]
     */
    public function getTransactionData(): Collection
    {
        return $this->transactionData;
    }

    public function addTransactionData(TransactionData $transactionData): self
    {
        if (!$this->transactionData->contains($transactionData)) {
            $this->transactionData[] = $transactionData;
            $transactionData->setTransactionID($this);
        }

        return $this;
    }

    public function removeTransactionData(TransactionData $transactionData): self
    {
        if ($this->transactionData->contains($transactionData)) {
            $this->transactionData->removeElement($transactionData);
            // set the owning side to null (unless already changed)
            if ($transactionData->getTransactionID() === $this) {
                $transactionData->setTransactionID(null);
            }
        }

        return $this;
    }
    
        /**
     * @return Collection|Archives[]
     */
    public function getArchives(): Collection
    {
        return $this->archives;
    }

    public function addArchive(Archives $archive): self
    {
        if (!$this->archives->contains($archive)) {
            $this->archives[] = $archive;
            $archive->setRelatedTransactionID($this);
        }

        return $this;
    }

    public function removeArchive(Archives $archive): self
    {
        if ($this->archives->contains($archive)) {
            $this->archives->removeElement($archive);
            // set the owning side to null (unless already changed)
            if ($archive->getRelatedTransactionID() === $this) {
                $archive->setRelatedTransactionID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CurrentTransactions[]
     */
    public function getCurrentTransactions(): Collection
    {
        return $this->currentTransactions;
    }

    public function addCurrentTransaction(CurrentTransactions $currentTransaction): self
    {
        if (!$this->currentTransactions->contains($currentTransaction)) {
            $this->currentTransactions[] = $currentTransaction;
            $currentTransaction->setTransactionID($this);
        }

        return $this;
    }

    public function removeCurrentTransaction(CurrentTransactions $currentTransaction): self
    {
        if ($this->currentTransactions->contains($currentTransaction)) {
            $this->currentTransactions->removeElement($currentTransaction);
            // set the owning side to null (unless already changed)
            if ($currentTransaction->getTransactionID() === $this) {
                $currentTransaction->setTransactionID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DisabledTransactionNotifications[]
     */
    public function getDisabledTransactionNotifications(): Collection
    {
        return $this->disabledTransactionNotifications;
    }

    public function addDisabledTransactionNotification(DisabledTransactionNotifications $disabledTransactionNotification): self
    {
        if (!$this->disabledTransactionNotifications->contains($disabledTransactionNotification)) {
            $this->disabledTransactionNotifications[] = $disabledTransactionNotification;
            $disabledTransactionNotification->setTransactionID($this);
        }

        return $this;
    }

    public function removeDisabledTransactionNotification(DisabledTransactionNotifications $disabledTransactionNotification): self
    {
        if ($this->disabledTransactionNotifications->contains($disabledTransactionNotification)) {
            $this->disabledTransactionNotifications->removeElement($disabledTransactionNotification);
            // set the owning side to null (unless already changed)
            if ($disabledTransactionNotification->getTransactionID() === $this) {
                $disabledTransactionNotification->setTransactionID(null);
            }
        }

        return $this;
    }
}
