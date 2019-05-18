<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExternalDataRepository")
 * @ORM\Table(name="externaldata")
 */
class ExternalData
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="externaldataID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ExternalSource", inversedBy="externalData")
     * @ORM\JoinColumn(name="sourceID", referencedColumnName="sourceID")
     */
    private $sourceID;

    /**
     * @ORM\ManyToOne(targetEntity="Entity", inversedBy="externalData")
     * @ORM\JoinColumn(name="entityID", referencedColumnName="entityID")
     */
    private $entityID;

    /**
     * @ORM\ManyToOne(targetEntity="Variable", inversedBy="externalData")
     * @ORM\JoinColumn(name="variableID", referencedColumnName="variableID")
     */
    private $variableID;

    /**
     * @ORM\Column(type="text")
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceID(): ?ExternalSource
    {
        return $this->sourceID;
    }

    public function setSourceID(?ExternalSource $sourceID): self
    {
        $this->sourceID = $sourceID;

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

    public function getVariableID(): ?Variable
    {
        return $this->variableID;
    }

    public function setVariableID(?Variable $variableID): self
    {
        $this->variableID = $variableID;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
