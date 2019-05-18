<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionDataRepository")
 * @ORM\Table(name="transactiondata")
 */
class TransactionData
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="transactiondataID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="UserTransactions", inversedBy="transactionData")
     * @ORM\JoinColumn(name="transactionID", referencedColumnName="transactionID")
     */
    private $transactionID;
    
    /**
     * @ORM\ManyToOne(targetEntity="Variable", inversedBy="transactionData")
     * @ORM\JoinColumn(name="variableID", referencedColumnName="variableID")
     */
    private $variableID;

    /**
     * @ORM\ManyToOne(targetEntity="VariableMode", inversedBy="transactionData")
     * @ORM\JoinColumn(name="modeID", referencedColumnName="modeID")
     */
    private $modeID;

    /**
     * @ORM\ManyToOne(targetEntity="VariableDisplayType", inversedBy="transactionData")
     * @ORM\JoinColumn(name="displayTypeID", referencedColumnName="displayTypeID")
     */
    private $displayTypeID;

    /**
     * @ORM\Column(type="string", length=10, nullable=true, name="userHistEnd")
     */
    private $userHistEnd;

    /**
     * @ORM\Column(type="string", length=10, nullable=true, name="actualHistEnd")
     */
    private $actualHistEnd;

    /**
     * @ORM\Column(type="text", nullable=true, name="inputValues")
     */
    private $inputValues;

    /**
     * @ORM\Column(type="text", nullable=true, name="outputValues")
     */
    private $outputValues;


    public function __construct()
    {
        $this->transactionQueues = new ArrayCollection();
        $this->archives = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
    
    public function getTransactionID(): ?UserTransactions
    {
        return $this->transactionID;
    }

    public function setTransactionID(?UserTransactions $transactionID): self
    {
        $this->transactionID = $transactionID;

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

    public function getUserHistEnd(): ?string
    {
        return $this->userHistEnd;
    }

    public function setUserHistEnd(?string $userHistEnd): self
    {
        $this->userHistEnd = $userHistEnd;

        return $this;
    }

    public function getActualHistEnd(): ?string
    {
        return $this->actualHistEnd;
    }

    public function setActualHistEnd(?string $actualHistEnd): self
    {
        $this->actualHistEnd = $actualHistEnd;

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
