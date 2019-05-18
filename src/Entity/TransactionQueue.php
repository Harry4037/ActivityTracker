<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionQueueRepository")
 * @ORM\Table(name="transactionqueue")
 */
class TransactionQueue
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="transactionqueueID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="UserTransactions", inversedBy="transactionQueues")
     * @ORM\JoinColumn(name="transactionID", referencedColumnName="transactionID")
     */
    private $transactionID;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="created_at")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransactionID(): ?UserTransactions
    {
        return $this->transactionID;
    }

    public function setTransactionID(?UserTransactions $transactionID): self
    {
        $this->transactionID = $transactionID;

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
