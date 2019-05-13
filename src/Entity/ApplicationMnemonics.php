<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApplicationMnemonicsRepository")
 * @ORM\Table(name="applicationmnemonics")
 */
class ApplicationMnemonics
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="applicationmnemonicID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="applicationMnemonics")
     * @ORM\JoinColumn(name="applicationID", referencedColumnName="applicationID")
     */
    private $applicationID;

    /**
     * @ORM\ManyToOne(targetEntity="Variable", inversedBy="applicationMnemonics")
     * @ORM\JoinColumn(name="variableID", referencedColumnName="variableID")
     */
    private $variableID;

    /**
     * @ORM\Column(type="string", length=255, name="mnemonic")
     */
    private $mnemonic;

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

    public function getVariableID(): ?Variable
    {
        return $this->variableID;
    }

    public function setVariableID(?Variable $variableID): self
    {
        $this->variableID = $variableID;

        return $this;
    }

    public function getMnemonic(): ?string
    {
        return $this->mnemonic;
    }

    public function setMnemonic(string $mnemonic): self
    {
        $this->mnemonic = $mnemonic;

        return $this;
    }
}
