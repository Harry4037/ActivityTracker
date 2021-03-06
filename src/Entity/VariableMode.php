<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VariableModeRepository")
 * @ORM\Table(name="variablemode")
 */
class VariableMode
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="modeID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, name="description")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=1, name="abbreviation")
     */
    private $abbreviation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TransactionData", mappedBy="modeID")
     */
    private $transactionData;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArchiveData", mappedBy="modeID")
     */
    private $archiveData;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BaseApplicationdata", mappedBy="modeID")
     */
    private $baseApplicationdatas;

    public function __construct()
    {
        $this->transactionData = new ArrayCollection();
        $this->archiveData = new ArrayCollection();
        $this->baseApplicationdatas = new ArrayCollection();
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

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(string $abbreviation): self
    {
        $this->abbreviation = $abbreviation;

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
            $transactionData->setModeID($this);
        }

        return $this;
    }

    public function removeTransactionData(TransactionData $transactionData): self
    {
        if ($this->transactionData->contains($transactionData)) {
            $this->transactionData->removeElement($transactionData);
            // set the owning side to null (unless already changed)
            if ($transactionData->getModeID() === $this) {
                $transactionData->setModeID(null);
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
            $archiveData->setModeID($this);
        }

        return $this;
    }

    public function removeArchiveData(ArchiveData $archiveData): self
    {
        if ($this->archiveData->contains($archiveData)) {
            $this->archiveData->removeElement($archiveData);
            // set the owning side to null (unless already changed)
            if ($archiveData->getModeID() === $this) {
                $archiveData->setModeID(null);
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
            $baseApplicationdata->setModeID($this);
        }

        return $this;
    }

    public function removeBaseApplicationdata(BaseApplicationdata $baseApplicationdata): self
    {
        if ($this->baseApplicationdatas->contains($baseApplicationdata)) {
            $this->baseApplicationdatas->removeElement($baseApplicationdata);
            // set the owning side to null (unless already changed)
            if ($baseApplicationdata->getModeID() === $this) {
                $baseApplicationdata->setModeID(null);
            }
        }

        return $this;
    }
}
