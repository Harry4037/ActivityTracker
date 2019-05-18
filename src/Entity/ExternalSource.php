<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExternalSourceRepository")
 * @ORM\Table(name="externalsource")
 */
class ExternalSource
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="sourceID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true, name="sourceName")
     */
    private $sourceName;

    /**
     * @ORM\Column(type="string", length=200, nullable=true, name="description")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExternalData", mappedBy="sourceID")
     */
    private $externalData;

    public function __construct()
    {
        $this->externalData = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceName(): ?string
    {
        return $this->sourceName;
    }

    public function setSourceName(?string $sourceName): self
    {
        $this->sourceName = $sourceName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
            $externalData->setSourceID($this);
        }

        return $this;
    }

    public function removeExternalData(ExternalData $externalData): self
    {
        if ($this->externalData->contains($externalData)) {
            $this->externalData->removeElement($externalData);
            // set the owning side to null (unless already changed)
            if ($externalData->getSourceID() === $this) {
                $externalData->setSourceID(null);
            }
        }

        return $this;
    }
}
