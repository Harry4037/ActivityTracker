<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArchiveDataRepository")
 * @ORM\Table(name="archivedata")
 */
class ArchiveData
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="archivedataID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Archives", inversedBy="archiveData")
     * @ORM\JoinColumn(name="archiveID", referencedColumnName="archiveID")
     */
    private $archiveID;

    /**
     * @ORM\ManyToOne(targetEntity="Variable", inversedBy="archiveData")
     * @ORM\JoinColumn(name="variableID", referencedColumnName="variableID")
     */
    private $variableID;

    /**
     * @ORM\ManyToOne(targetEntity="VariableMode", inversedBy="archiveData")
     * @ORM\JoinColumn(name="modeID", referencedColumnName="modeID")
     */
    private $modeID;

    /**
     * @ORM\ManyToOne(targetEntity="VariableDisplayType", inversedBy="archiveData")
     * @ORM\JoinColumn(name="displayTypeID", referencedColumnName="displayTypeID")
     */
    private $displayTypeID;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $inputValues;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $outputValues;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArchiveID(): ?Archives
    {
        return $this->archiveID;
    }

    public function setArchiveID(?Archives $archiveID): self
    {
        $this->archiveID = $archiveID;

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

    public function getModeID(): ?VariableMode
    {
        return $this->modeID;
    }

    public function setModeID(?VariableMode $modeID): self
    {
        $this->modeID = $modeID;

        return $this;
    }

    public function getDisplayTypeID(): ?VariableDisplayType
    {
        return $this->displayTypeID;
    }

    public function setDisplayTypeID(?VariableDisplayType $displayTypeID): self
    {
        $this->displayTypeID = $displayTypeID;

        return $this;
    }

    public function getInputValues(): ?string
    {
        return $this->inputValues;
    }

    public function setInputValues(?string $inputValues): self
    {
        $this->inputValues = $inputValues;

        return $this;
    }

    public function getOutputValues(): ?string
    {
        return $this->outputValues;
    }

    public function setOutputValues(?string $outputValues): self
    {
        $this->outputValues = $outputValues;

        return $this;
    }
}
