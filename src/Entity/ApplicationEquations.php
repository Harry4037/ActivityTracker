<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApplicationEquationsRepository")
 * @ORM\Table(name="applicationequations")
 */
class ApplicationEquations
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="applicationequationID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="applicationEquations")
     * @ORM\JoinColumn(name="applicationID", referencedColumnName="applicationID")
     */
    private $applicationID;

    /**
     * @ORM\ManyToOne(targetEntity="Entity", inversedBy="applicationEquations")
     * @ORM\JoinColumn(name="entityID", referencedColumnName="entityID")
     */
    private $entityID;

    /**
     * @ORM\Column(type="text", name="equation")
     */
    private $equation;

    /**
     * @ORM\ManyToOne(targetEntity="Variable", inversedBy="applicationEquations")
     * @ORM\JoinColumn(name="variableID", referencedColumnName="variableID")
     */
    private $variableID;

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

    public function getEquation(): ?string
    {
        return $this->equation;
    }

    public function setEquation(string $equation): self
    {
        $this->equation = $equation;

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
}
