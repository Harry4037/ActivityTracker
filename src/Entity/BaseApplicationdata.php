<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BaseApplicationdataRepository")
 * @ORM\Table(name="baseapplicationdata")
 */
class BaseApplicationdata
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="baseapplicationdataID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="baseApplicationdatas")
     * @ORM\JoinColumn(name="applicationID", referencedColumnName="applicationID")
     */
    private $applicationID;

    /**
     * @ORM\ManyToOne(targetEntity="Entity", inversedBy="baseApplicationdatas")
     * @ORM\JoinColumn(name="entityID", referencedColumnName="entityID")
     */
    private $entityID;

    /**
     * @ORM\ManyToOne(targetEntity="Variable", inversedBy="baseApplicationdatas")
     * @ORM\JoinColumn(name="variableID", referencedColumnName="variableID")
     */
    private $variableID;

    /**
     * @ORM\ManyToOne(targetEntity="VariableMode", inversedBy="baseApplicationdatas")
     * @ORM\JoinColumn(name="modeID", referencedColumnName="modeID")
     */
    private $modeID;

    /**
     * @ORM\ManyToOne(targetEntity="VariableDisplayType", inversedBy="baseApplicationdatas")
     * @ORM\JoinColumn(name="displayTypeID", referencedColumnName="displayTypeID")
     */
    private $displayTypeID;

    /**
     * @ORM\Column(type="string", length=10, nullable=true, name="userHisEnd")
     */
    private $userHisEnd;

    /**
     * @ORM\Column(type="string", length=10, nullable=true, name="actualHisEnd")
     */
    private $actualHisEnd;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true, name="data")
     */
    private $data;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApplicationID(): ?Application
    {
        return $this->applicationID;
    }

    public function setApplicationID(?Application $applicationID): self
    {
        $this->applicationID = $applicationID;

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

    public function getUserHisEnd(): ?string
    {
        return $this->userHisEnd;
    }

    public function setUserHisEnd(?string $userHisEnd): self
    {
        $this->userHisEnd = $userHisEnd;

        return $this;
    }

    public function getActualHisEnd(): ?string
    {
        return $this->actualHisEnd;
    }

    public function setActualHisEnd(?string $actualHisEnd): self
    {
        $this->actualHisEnd = $actualHisEnd;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): self
    {
        $this->data = $data;

        return $this;
    }
}
