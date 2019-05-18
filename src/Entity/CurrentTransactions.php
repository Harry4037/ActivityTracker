<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CurrentTransactionsRepository")
 * @ORM\Table(name="currenttransactions")
 */
class CurrentTransactions
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="currenttransactionID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Groups", inversedBy="currentTransactions")
     * @ORM\JoinColumn(name="groupID", referencedColumnName="groupID")
     */
    private $groupID;

    /**
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="currentTransactions")
     * @ORM\JoinColumn(name="applicationID", referencedColumnName="applicationID")
     */
    private $applicationID;

    /**
     * @ORM\ManyToOne(targetEntity="Entity", inversedBy="currentTransactions")
     * @ORM\JoinColumn(name="entityID", referencedColumnName="entityID")
     */
    private $entityID;

    /**
     * @ORM\ManyToOne(targetEntity="UserTransactions", inversedBy="currentTransactions")
     * @ORM\JoinColumn(name="transactionID", referencedColumnName="transactionID")
     */
    private $transactionID;

    /**
     * @ORM\ManyToOne(targetEntity="UserTransactions", inversedBy="currentTransactions")
     * @ORM\JoinColumn(name="previousTransactionID", referencedColumnName="transactionID")
     */
    private $previousTransactionID;

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

    public function getEntityID(): ?Entity
    {
        return $this->entityID;
    }

    public function setEntityID(?Entity $entityID): self
    {
        $this->entityID = $entityID;

        return $this;
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

    public function getPreviousTransactionID(): ?UserTransactions
    {
        return $this->previousTransactionID;
    }

    public function setPreviousTransactionID(?UserTransactions $previousTransactionID): self
    {
        $this->previousTransactionID = $previousTransactionID;

        return $this;
    }
}
