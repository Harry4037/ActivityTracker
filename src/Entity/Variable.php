<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VariableRepository")
 * @ORM\Table(name="variable")
 */
class Variable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="variableID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200, name="description")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ApplicationEquations", mappedBy="variableID")
     */
    private $applicationEquations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ApplicationMnemonics", mappedBy="variableID")
     */
    private $applicationMnemonics;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TransactionData", mappedBy="variableID")
     */
    private $transactionData;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArchiveData", mappedBy="variableID")
     */
    private $archiveData;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BaseApplicationdata", mappedBy="variableID")
     */
    private $baseApplicationdatas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExternalData", mappedBy="variableID")
     */
    private $externalData;

    public function __construct()
    {
        $this->applicationEquations = new ArrayCollection();
        $this->applicationMnemonics = new ArrayCollection();
        $this->transactionData = new ArrayCollection();
        $this->archiveData = new ArrayCollection();
        $this->baseApplicationdatas = new ArrayCollection();
        $this->externalData = new ArrayCollection();
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
            $applicationEquation->setVariableID($this);
        }

        return $this;
    }

    public function removeApplicationEquation(ApplicationEquations $applicationEquation): self
    {
        if ($this->applicationEquations->contains($applicationEquation)) {
            $this->applicationEquations->removeElement($applicationEquation);
            // set the owning side to null (unless already changed)
            if ($applicationEquation->getVariableID() === $this) {
                $applicationEquation->setVariableID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ApplicationMnemonics[]
     */
    public function getApplicationMnemonics(): Collection
    {
        return $this->applicationMnemonics;
    }

    public function addApplicationMnemonic(ApplicationMnemonics $applicationMnemonic): self
    {
        if (!$this->applicationMnemonics->contains($applicationMnemonic)) {
            $this->applicationMnemonics[] = $applicationMnemonic;
            $applicationMnemonic->setVariableID($this);
        }

        return $this;
    }

    public function removeApplicationMnemonic(ApplicationMnemonics $applicationMnemonic): self
    {
        if ($this->applicationMnemonics->contains($applicationMnemonic)) {
            $this->applicationMnemonics->removeElement($applicationMnemonic);
            // set the owning side to null (unless already changed)
            if ($applicationMnemonic->getVariableID() === $this) {
                $applicationMnemonic->setVariableID(null);
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
            $transactionData->setVariableID($this);
        }

        return $this;
    }

    public function removeTransactionData(TransactionData $transactionData): self
    {
        if ($this->transactionData->contains($transactionData)) {
            $this->transactionData->removeElement($transactionData);
            // set the owning side to null (unless already changed)
            if ($transactionData->getVariableID() === $this) {
                $transactionData->setVariableID(null);
            }
        }

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
            $archiveData->setVariableID($this);
        }

        return $this;
    }

    public function removeArchiveData(ArchiveData $archiveData): self
    {
        if ($this->archiveData->contains($archiveData)) {
            $this->archiveData->removeElement($archiveData);
            // set the owning side to null (unless already changed)
            if ($archiveData->getVariableID() === $this) {
                $archiveData->setVariableID(null);
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
            $baseApplicationdata->setVariableID($this);
        }

        return $this;
    }

    public function removeBaseApplicationdata(BaseApplicationdata $baseApplicationdata): self
    {
        if ($this->baseApplicationdatas->contains($baseApplicationdata)) {
            $this->baseApplicationdatas->removeElement($baseApplicationdata);
            // set the owning side to null (unless already changed)
            if ($baseApplicationdata->getVariableID() === $this) {
                $baseApplicationdata->setVariableID(null);
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
            $externalData->setVariableID($this);
        }

        return $this;
    }

    public function removeExternalData(ExternalData $externalData): self
    {
        if ($this->externalData->contains($externalData)) {
            $this->externalData->removeElement($externalData);
            // set the owning side to null (unless already changed)
            if ($externalData->getVariableID() === $this) {
                $externalData->setVariableID(null);
            }
        }

        return $this;
    }
}
