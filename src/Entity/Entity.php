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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EntityInApplication", mappedBy="entityID")
     */
    private $entityInApplications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ApplicationEquations", mappedBy="entityID")
     */
    private $applicationEquations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Archives", mappedBy="entityID")
     */
    private $archives;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BaseApplicationdata", mappedBy="entityID")
     */
    private $baseApplicationdatas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CurrentTransactions", mappedBy="entityID")
     */
    private $currentTransactions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EntityInAggregates", mappedBy="aggregateID")
     */
    private $entityInAggregates;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExternalData", mappedBy="entityID")
     */
    private $externalData;

    public function __construct()
    {
        $this->userTransactions = new ArrayCollection();
        $this->recentSimulations = new ArrayCollection();
        $this->entityInApplications = new ArrayCollection();
        $this->applicationEquations = new ArrayCollection();
        $this->archives = new ArrayCollection();
        $this->baseApplicationdatas = new ArrayCollection();
        $this->currentTransactions = new ArrayCollection();
        $this->entityInAggregates = new ArrayCollection();
        $this->externalData = new ArrayCollection();
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

    /**
     * @return Collection|EntityInApplication[]
     */
    public function getEntityInApplications(): Collection
    {
        return $this->entityInApplications;
    }

    public function addEntityInApplication(EntityInApplication $entityInApplication): self
    {
        if (!$this->entityInApplications->contains($entityInApplication)) {
            $this->entityInApplications[] = $entityInApplication;
            $entityInApplication->setEntityID($this);
        }

        return $this;
    }

    public function removeEntityInApplication(EntityInApplication $entityInApplication): self
    {
        if ($this->entityInApplications->contains($entityInApplication)) {
            $this->entityInApplications->removeElement($entityInApplication);
            // set the owning side to null (unless already changed)
            if ($entityInApplication->getEntityID() === $this) {
                $entityInApplication->setEntityID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ApplicationEquations[]
     */
    public function getApplicationEquations(): Collection
    {
        return $this->applicationEquations;
    }

    public function addApplicationEquation(ApplicationEquations $applicationEquation): self
    {
        if (!$this->applicationEquations->contains($applicationEquation)) {
            $this->applicationEquations[] = $applicationEquation;
            $applicationEquation->setEntityID($this);
        }

        return $this;
    }

    public function removeApplicationEquation(ApplicationEquations $applicationEquation): self
    {
        if ($this->applicationEquations->contains($applicationEquation)) {
            $this->applicationEquations->removeElement($applicationEquation);
            // set the owning side to null (unless already changed)
            if ($applicationEquation->getEntityID() === $this) {
                $applicationEquation->setEntityID(null);
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
            $archive->setEntityID($this);
        }

        return $this;
    }

    public function removeArchive(Archives $archive): self
    {
        if ($this->archives->contains($archive)) {
            $this->archives->removeElement($archive);
            // set the owning side to null (unless already changed)
            if ($archive->getEntityID() === $this) {
                $archive->setEntityID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BaseApplicationdata[]
     */
    public function getBaseApplicationdatas(): Collection
    {
        return $this->baseApplicationdatas;
    }

    public function addBaseApplicationdata(BaseApplicationdata $baseApplicationdata): self
    {
        if (!$this->baseApplicationdatas->contains($baseApplicationdata)) {
            $this->baseApplicationdatas[] = $baseApplicationdata;
            $baseApplicationdata->setEntityID($this);
        }

        return $this;
    }

    public function removeBaseApplicationdata(BaseApplicationdata $baseApplicationdata): self
    {
        if ($this->baseApplicationdatas->contains($baseApplicationdata)) {
            $this->baseApplicationdatas->removeElement($baseApplicationdata);
            // set the owning side to null (unless already changed)
            if ($baseApplicationdata->getEntityID() === $this) {
                $baseApplicationdata->setEntityID(null);
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
            $currentTransaction->setEntityID($this);
        }

        return $this;
    }

    public function removeCurrentTransaction(CurrentTransactions $currentTransaction): self
    {
        if ($this->currentTransactions->contains($currentTransaction)) {
            $this->currentTransactions->removeElement($currentTransaction);
            // set the owning side to null (unless already changed)
            if ($currentTransaction->getEntityID() === $this) {
                $currentTransaction->setEntityID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EntityInAggregates[]
     */
    public function getEntityInAggregates(): Collection
    {
        return $this->entityInAggregates;
    }

    public function addEntityInAggregate(EntityInAggregates $entityInAggregate): self
    {
        if (!$this->entityInAggregates->contains($entityInAggregate)) {
            $this->entityInAggregates[] = $entityInAggregate;
            $entityInAggregate->setAggregateID($this);
        }

        return $this;
    }

    public function removeEntityInAggregate(EntityInAggregates $entityInAggregate): self
    {
        if ($this->entityInAggregates->contains($entityInAggregate)) {
            $this->entityInAggregates->removeElement($entityInAggregate);
            // set the owning side to null (unless already changed)
            if ($entityInAggregate->getAggregateID() === $this) {
                $entityInAggregate->setAggregateID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ExternalData[]
     */
    public function getExternalData(): Collection
    {
        return $this->externalData;
    }

    public function addExternalData(ExternalData $externalData): self
    {
        if (!$this->externalData->contains($externalData)) {
            $this->externalData[] = $externalData;
            $externalData->setEntityID($this);
        }

        return $this;
    }

    public function removeExternalData(ExternalData $externalData): self
    {
        if ($this->externalData->contains($externalData)) {
            $this->externalData->removeElement($externalData);
            // set the owning side to null (unless already changed)
            if ($externalData->getEntityID() === $this) {
                $externalData->setEntityID(null);
            }
        }

        return $this;
    }
}
