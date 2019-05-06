<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandRepository")
 * @ORM\Table(name="command")
 */
class Command
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="commandID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="UserTransactions", mappedBy="commandID")
     */
    private $userTransactions;

    public function __construct()
    {
        $this->userTransactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|UserTransactions[]
     */
    public function getUserTransactions(): Collection
    {
        return $this->userTransactions;
    }

    public function addUserTransaction(UserTransactions $userTransaction): self
    {
        if (!$this->userTransactions->contains($userTransaction)) {
            $this->userTransactions[] = $userTransaction;
            $userTransaction->setCommandID($this);
        }

        return $this;
    }

    public function removeUserTransaction(UserTransactions $userTransaction): self
    {
        if ($this->userTransactions->contains($userTransaction)) {
            $this->userTransactions->removeElement($userTransaction);
            // set the owning side to null (unless already changed)
            if ($userTransaction->getCommandID() === $this) {
                $userTransaction->setCommandID(null);
            }
        }

        return $this;
    }
}
