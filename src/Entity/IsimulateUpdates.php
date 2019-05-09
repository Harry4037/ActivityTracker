<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IsimulateUpdatesRepository")
 * @ORM\Table(name="isimulateupdates")
 */
class IsimulateUpdates
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="updateID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="text")
     */
    private $text;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="created_at")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
