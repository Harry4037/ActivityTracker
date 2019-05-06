<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApplicationRepository")
 * @ORM\Table(name="application")
 */
class Application
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="applicationID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $applicationName;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $organization;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=75)
     */
    private $callbackURL;

    /**
     * @ORM\Column(type="string", columnDefinition="CHAR(40) NOT NULL")
     */
    private $secretKey;

    /**
     * @ORM\Column(type="string", columnDefinition="CHAR(32) NOT NULL")
     */
    private $nonce;

    /**
     * @ORM\Column(type="integer", columnDefinition="TINYINT(1) NULL")
     */
    private $approveToJoin;

    /**
     * @ORM\Column(type="string", columnDefinition="CHAR(1) NOT NULL")
     */
    private $frequency;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $startDataPeriod;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $endDataPeriod;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $startSimulationPeriod;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $endSimulationPeriod;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $startDisplayPeriod;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $endDisplayPeriod;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $startUserSolvePeriod;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $endUserSolvePeriod;

    /**
     * @ORM\Column(type="integer", columnDefinition="TINYINT(1) DEFAULT 1 NOT NULL")
     */
    private $inDevelopment;

    /**
     * @ORM\Column(type="integer", columnDefinition="TINYINT(1) DEFAULT 0 NOT NULL")
     */
    private $approved;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity="ApplicationAdmins", mappedBy="applicationID", orphanRemoval=true)
     */
    private $applicationAdmins;

    /**
     * @ORM\OneToMany(targetEntity="ApplicationRequests", mappedBy="applicationID", orphanRemoval=true)
     */
    private $applicationRequests;

    /**
     * @ORM\OneToMany(targetEntity="UserTransactions", mappedBy="applicationID")
     */
    private $userTransactions;

    public function __construct()
    {
        $this->applicationAdmins = new ArrayCollection();
        $this->applicationRequests = new ArrayCollection();
        $this->userTransactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApplicationName(): ?string
    {
        return $this->applicationName;
    }

    public function setApplicationName(?string $applicationName): self
    {
        $this->applicationName = $applicationName;

        return $this;
    }

    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    public function setOrganization(?string $organization): self
    {
        $this->organization = $organization;

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

    public function getCallbackURL(): ?string
    {
        return $this->callbackURL;
    }

    public function setCallbackURL(string $callbackURL): self
    {
        $this->callbackURL = $callbackURL;

        return $this;
    }

    public function getSecretKey(): ?string
    {
        return $this->secretKey;
    }

    public function setSecretKey(string $secretKey): self
    {
        $this->secretKey = $secretKey;

        return $this;
    }

    public function getNonce(): ?string
    {
        return $this->nonce;
    }

    public function setNonce(string $nonce): self
    {
        $this->nonce = $nonce;

        return $this;
    }

    public function getApproveToJoin(): ?int
    {
        return $this->approveToJoin;
    }

    public function setApproveToJoin(?int $approveToJoin): self
    {
        $this->approveToJoin = $approveToJoin;

        return $this;
    }

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(string $frequency): self
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getStartDataPeriod(): ?string
    {
        return $this->startDataPeriod;
    }

    public function setStartDataPeriod(string $startDataPeriod): self
    {
        $this->startDataPeriod = $startDataPeriod;

        return $this;
    }

    public function getEndDataPeriod(): ?string
    {
        return $this->endDataPeriod;
    }

    public function setEndDataPeriod(string $endDataPeriod): self
    {
        $this->endDataPeriod = $endDataPeriod;

        return $this;
    }

    public function getStartSimulationPeriod(): ?string
    {
        return $this->startSimulationPeriod;
    }

    public function setStartSimulationPeriod(string $startSimulationPeriod): self
    {
        $this->startSimulationPeriod = $startSimulationPeriod;

        return $this;
    }

    public function getEndSimulationPeriod(): ?string
    {
        return $this->endSimulationPeriod;
    }

    public function setEndSimulationPeriod(string $endSimulationPeriod): self
    {
        $this->endSimulationPeriod = $endSimulationPeriod;

        return $this;
    }

    public function getStartDisplayPeriod(): ?string
    {
        return $this->startDisplayPeriod;
    }

    public function setStartDisplayPeriod(string $startDisplayPeriod): self
    {
        $this->startDisplayPeriod = $startDisplayPeriod;

        return $this;
    }

    public function getEndDisplayPeriod(): ?string
    {
        return $this->endDisplayPeriod;
    }

    public function setEndDisplayPeriod(string $endDisplayPeriod): self
    {
        $this->endDisplayPeriod = $endDisplayPeriod;

        return $this;
    }

    public function getStartUserSolvePeriod(): ?string
    {
        return $this->startUserSolvePeriod;
    }

    public function setStartUserSolvePeriod(string $startUserSolvePeriod): self
    {
        $this->startUserSolvePeriod = $startUserSolvePeriod;

        return $this;
    }

    public function getEndUserSolvePeriod(): ?string
    {
        return $this->endUserSolvePeriod;
    }

    public function setEndUserSolvePeriod(string $endUserSolvePeriod): self
    {
        $this->endUserSolvePeriod = $endUserSolvePeriod;

        return $this;
    }

    public function getInDevelopment(): ?int
    {
        return $this->inDevelopment;
    }

    public function setInDevelopment(int $inDevelopment): self
    {
        $this->inDevelopment = $inDevelopment;

        return $this;
    }

    public function getApproved(): ?int
    {
        return $this->approved;
    }

    public function setApproved(int $approved): self
    {
        $this->approved = $approved;

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

    /**
     * @return Collection|ApplicationAdmins[]
     */
    public function getApplicationAdmins(): Collection
    {
        return $this->applicationAdmins;
    }

    public function addApplicationAdmin(ApplicationAdmins $applicationAdmin): self
    {
        if (!$this->applicationAdmins->contains($applicationAdmin)) {
            $this->applicationAdmins[] = $applicationAdmin;
            $applicationAdmin->setApplicationID($this);
        }

        return $this;
    }

    public function removeApplicationAdmin(ApplicationAdmins $applicationAdmin): self
    {
        if ($this->applicationAdmins->contains($applicationAdmin)) {
            $this->applicationAdmins->removeElement($applicationAdmin);
            // set the owning side to null (unless already changed)
            if ($applicationAdmin->getApplicationID() === $this) {
                $applicationAdmin->setApplicationID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ApplicationRequests[]
     */
    public function getApplicationRequests(): Collection
    {
        return $this->applicationRequests;
    }

    public function addApplicationRequest(ApplicationRequests $applicationRequest): self
    {
        if (!$this->applicationRequests->contains($applicationRequest)) {
            $this->applicationRequests[] = $applicationRequest;
            $applicationRequest->setApplicationID($this);
        }

        return $this;
    }

    public function removeApplicationRequest(ApplicationRequests $applicationRequest): self
    {
        if ($this->applicationRequests->contains($applicationRequest)) {
            $this->applicationRequests->removeElement($applicationRequest);
            // set the owning side to null (unless already changed)
            if ($applicationRequest->getApplicationID() === $this) {
                $applicationRequest->setApplicationID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserTransactions[]
     */
    public function getUserTransactions(): Collection
    {
        return $this->userTransactions;
    }

    public function addUserTransaction(UserTransactions $userTransaction): self
    {
        if (!$this->userTransactions->contains($userTransaction)) {
            $this->userTransactions[] = $userTransaction;
            $userTransaction->setApplicationID($this);
        }

        return $this;
    }

    public function removeUserTransaction(UserTransactions $userTransaction): self
    {
        if ($this->userTransactions->contains($userTransaction)) {
            $this->userTransactions->removeElement($userTransaction);
            // set the owning side to null (unless already changed)
            if ($userTransaction->getApplicationID() === $this) {
                $userTransaction->setApplicationID(null);
            }
        }

        return $this;
    }
}
