<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DisabledTransactionNotificationsRepository")
 * @ORM\Table(name="disabledtransactionnotifications")
 */
class DisabledTransactionNotifications
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="disabledtransactionID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="disabledTransactionNotifications")
     * @ORM\JoinColumn(name="userID", referencedColumnName="userID")
     */
    private $userID;

    /**
     * @ORM\ManyToOne(targetEntity="UserTransactions", inversedBy="disabledTransactionNotifications")
     * @ORM\JoinColumn(name="transactionID", referencedColumnName="transactionID")
     */
    private $transactionID;

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

    public function getTransactionID(): ?UserTransactions
    {
        return $this->transactionID;
    }

    public function setTransactionID(?UserTransactions $transactionID): self
    {
        $this->transactionID = $transactionID;

        return $this;
    }
}
