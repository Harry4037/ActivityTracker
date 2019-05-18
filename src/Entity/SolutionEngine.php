<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SolutionEngineRepository")
 * @ORM\Table(name="Solutionengine")
 */
class SolutionEngine
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="engineID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=75, nullable=true, name="engineName")
     */
    private $engineName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEngineName(): ?string
    {
        return $this->engineName;
    }

    public function setEngineName(?string $engineName): self
    {
        $this->engineName = $engineName;

        return $this;
    }
}
