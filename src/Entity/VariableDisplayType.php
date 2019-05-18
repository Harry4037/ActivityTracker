<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VariableDisplayTypeRepository")
 * @ORM\Table(name="variabledisplaytype")
 */
class VariableDisplayType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="displayTypeID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, name="description")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=5, name="abbreviation")
     */
    private $abbreviation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TransactionData", mappedBy="displayTypeID")
     */
    private $transactionData;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArchiveData", mappedBy="displayTypeID")
     */
    private $archiveData;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BaseApplicationdata", mappedBy="displayTypeID")
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
            $transactionData->setDisplayTypeID($this);
        }

        return $this;
    }

    public function removeTransactionData(TransactionData $transactionData): self
    {
        if ($this->transactionData->contains($transactionData)) {
            $this->transactionData->removeElement($transactionData);
            // set the owning side to null (unless already changed)
            if ($transactionData->getDisplayTypeID() === $this) {
                $transactionData->setDisplayTypeID(null);
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
            $archiveData->setDisplayTypeID($this);
        }

        return $this;
    }

    public function removeArchiveData(ArchiveData $archiveData): self
    {
        if ($this->archiveData->contains($archiveData)) {
            $this->archiveData->removeElement($archiveData);
            // set the owning side to null (unless already changed)
            if ($archiveData->getDisplayTypeID() === $this) {
                $archiveData->setDisplayTypeID(null);
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
            $baseApplicationdata->setDisplayTypeID($this);
        }

        return $this;
    }

    public function removeBaseApplicationdata(BaseApplicationdata $baseApplicationdata): self
    {
        if ($this->baseApplicationdatas->contains($baseApplicationdata)) {
            $this->baseApplicationdatas->removeElement($baseApplicationdata);
            // set the owning side to null (unless already changed)
            if ($baseApplicationdata->getDisplayTypeID() === $this) {
                $baseApplicationdata->setDisplayTypeID(null);
            }
        }

        return $this;
    }
}
