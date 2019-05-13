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

    public function __construct()
    {
        $this->applicationEquations = new ArrayCollection();
        $this->applicationMnemonics = new ArrayCollection();
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
}
