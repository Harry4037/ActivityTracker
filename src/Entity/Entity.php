<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntityRepository")
 * @ORM\Table(name="entity")
 */
class Entity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="entityID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $entityCode;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $entityName;

    /**
     * @ORM\OneToMany(targetEntity="UserTransactions", mappedBy="entityID")
     */
    private $userTransactions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecentSimulations", mappedBy="entityID")
     */
    private $recentSimulations;

    public function __construct()
    {
        $this->userTransactions = new ArrayCollection();
        $this->recentSimulations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntityCode(): ?string
    {
        return $this->entityCode;
    }

    public function setEntityCode(?string $entityCode): self
    {
        $this->entityCode = $entityCode;

        return $this;
    }

    public function getEntityName(): ?string
    {
        return $this->entityName;
    }

    public function setEntityName(?string $entityName): self
    {
        $this->entityName = $entityName;

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
            $userTransaction->setEntityID($this);
        }

        return $this;
    }

    public function removeUserTransaction(UserTransactions $userTransaction): self
    {
        if ($this->userTransactions->contains($userTransaction)) {
            $this->userTransactions->removeElement($userTransaction);
            // set the owning side to null (unless already changed)
            if ($userTransaction->getEntityID() === $this) {
                $userTransaction->setEntityID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RecentSimulations[]
     */
    public function getRecentSimulations(): Collection
    {
        return $this->recentSimulations;
    }

    public function addRecentSimulation(RecentSimulations $recentSimulation): self
    {
        if (!$this->recentSimulations->contains($recentSimulation)) {
            $this->recentSimulations[] = $recentSimulation;
            $recentSimulation->setEntityID($this);
        }

        return $this;
    }

    public function removeRecentSimulation(RecentSimulations $recentSimulation): self
    {
        if ($this->recentSimulations->contains($recentSimulation)) {
            $this->recentSimulations->removeElement($recentSimulation);
            // set the owning side to null (unless already changed)
            if ($recentSimulation->getEntityID() === $this) {
                $recentSimulation->setEntityID(null);
            }
        }

        return $this;
    }
}
