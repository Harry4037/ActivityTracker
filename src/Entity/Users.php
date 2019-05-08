<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @UniqueEntity(fields={"email"}, message="It looks like your already have an account, Email already in use.")
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 */
class Users implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer",name="userID")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string",name="username", unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string",name="firstName")
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string",name="lastName")
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(type="string",name="email", unique=true)
     * @Assert\Email()
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string",name="affiliation")
     * @Assert\Length(min=2, max=150)
     */
    private $affiliation;

    /**
     * @var string
     * @ORM\Column(type="string",options={"default":"America/New_York"})
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=150)
     */
    private $timezone;

    /**
     * @var string
     * @ORM\Column(type="string",name="displayName",options={"default":"real"})
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=150)
     */
    private $displayName;

    /**
     * @var integer
     * @ORM\Column(type="integer",name="invitesRemaining",columnDefinition="TINYINT DEFAULT 50 NOT NULL")
     * @Assert\NotBlank()
     */
    private $invitesRemaining;

    /**
     * @var integer
     * @ORM\Column(type="integer",name="showGetStartedBox",columnDefinition="TINYINT DEFAULT true NOT NULL")
     * @Assert\NotBlank()
     */
    private $showGetStartedBox;

    /**
     * @var integer
     * @ORM\Column(type="integer",name="iChartInternalAccess",columnDefinition="TINYINT DEFAULT 0 NOT NULL")
     * @Assert\NotBlank()
     */
    private $iChartInternalAccess;

    /**
     * @var integer
     * @ORM\Column(type="integer",name="activated",columnDefinition="TINYINT DEFAULT 0 NOT NULL")
     * @Assert\NotBlank()
     */
    private $activated;

    /**
     * @var string
     * @ORM\Column(type="string",name="activationCode")
     * @Assert\Length(min=2, max=45)
     */
    private $activationCode;

    /**
     * @var string
     * @ORM\Column(type="string",name="newPasswordCode")
     * @Assert\Length(min=2, max=45)
     */
    private $newPasswordCode;

    /**
     * @var integer
     * @ORM\Column(type="integer",name="loginAttempts",columnDefinition="TINYINT DEFAULT 0 NOT NULL")
     * @Assert\NotBlank()
     */
    private $loginAttempts;

    /**
     * @var /datetime $newPasswordTime
     *
     * @ORM\Column(type="datetime")
     */
    private $newPasswordTime;

    /**
     * @var /datetime $created_at
     *
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @var /datetime $lastLoginAttempt
     *
     * @ORM\Column(type="datetime")
     */
    private $lastLoginAttempt;

    /**
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetPassword;

    /**
     * @ORM\OneToMany(targetEntity="GroupMembers", mappedBy="userID", orphanRemoval=true)
     */
    private $groupMembers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupRequests", mappedBy="userID")
     */
    private $groupRequests;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ApplicationAdmins", mappedBy="userID", orphanRemoval=true)
     */
    private $applicationAdmins;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserTransactions", mappedBy="userID")
     */
    private $userTransactions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupMembershipNotifications", mappedBy="userID")
     */
    private $groupMembershipNotifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupApplicationNotifications", mappedBy="userID")
     */
    private $groupApplicationNotifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecentSimulations", mappedBy="userID")
     */
    private $recentSimulations;

    public function __construct()
    {
        $this->groupMembers = new ArrayCollection();
        $this->groupRequests = new ArrayCollection();
        $this->applicationAdmins = new ArrayCollection();
        $this->userTransactions = new ArrayCollection();
        $this->groupMembershipNotifications = new ArrayCollection();
        $this->groupApplicationNotifications = new ArrayCollection();
        $this->recentSimulations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new \DateTime("now");
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // See "Do you need to use a Salt?" at https://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one

        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // if you had a plainPassword property, you'd nullify it here
        // $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): ?string
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        return serialize([$this->id, $this->username, $this->password]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getResetPassword(): ?string
    {
        return $this->resetPassword;
    }

    public function setResetPassword(string $resetPassword): self
    {
        $this->resetPassword = $resetPassword;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAffiliation(): ?string
    {
        return $this->affiliation;
    }

    public function setAffiliation(string $affiliation): self
    {
        $this->affiliation = $affiliation;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getInvitesRemaining(): ?int
    {
        return $this->invitesRemaining;
    }

    public function setInvitesRemaining(int $invitesRemaining): self
    {
        $this->invitesRemaining = $invitesRemaining;

        return $this;
    }

    public function getShowGetStartedBox(): ?int
    {
        return $this->showGetStartedBox;
    }

    public function setShowGetStartedBox(int $showGetStartedBox): self
    {
        $this->showGetStartedBox = $showGetStartedBox;

        return $this;
    }

    public function getIChartInternalAccess(): ?int
    {
        return $this->iChartInternalAccess;
    }

    public function setIChartInternalAccess(int $iChartInternalAccess): self
    {
        $this->iChartInternalAccess = $iChartInternalAccess;

        return $this;
    }

    public function getActivated(): ?int
    {
        return $this->activated;
    }

    public function setActivated(int $activated): self
    {
        $this->activated = $activated;

        return $this;
    }

    public function getActivationCode(): ?string
    {
        return $this->activationCode;
    }

    public function setActivationCode(string $activationCode): self
    {
        $this->activationCode = $activationCode;

        return $this;
    }

    public function getNewPasswordCode(): ?string
    {
        return $this->newPasswordCode;
    }

    public function setNewPasswordCode(string $newPasswordCode): self
    {
        $this->newPasswordCode = $newPasswordCode;

        return $this;
    }

    public function getLoginAttempts(): ?int
    {
        return $this->loginAttempts;
    }

    public function setLoginAttempts(int $loginAttempts): self
    {
        $this->loginAttempts = $loginAttempts;

        return $this;
    }

    public function getNewPasswordTime(): ?\DateTimeInterface
    {
        return $this->newPasswordTime;
    }

    public function setNewPasswordTime(\DateTimeInterface $newPasswordTime): self
    {
        $this->newPasswordTime = $newPasswordTime;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getLastLoginAttempt(): ?\DateTimeInterface
    {
        return $this->lastLoginAttempt;
    }

    public function setLastLoginAttempt(\DateTimeInterface $lastLoginAttempt): self
    {
        $this->lastLoginAttempt = $lastLoginAttempt;

        return $this;
    }

    /**
     * @return Collection|GroupMembers[]
     */
    public function getGroupMembers(): Collection
    {
        return $this->groupMembers;
    }

    public function addGroupMember(GroupMembers $groupMember): self
    {
        if (!$this->groupMembers->contains($groupMember)) {
            $this->groupMembers[] = $groupMember;
            $groupMember->setUserID($this);
        }

        return $this;
    }

    public function removeGroupMember(GroupMembers $groupMember): self
    {
        if ($this->groupMembers->contains($groupMember)) {
            $this->groupMembers->removeElement($groupMember);
            // set the owning side to null (unless already changed)
            if ($groupMember->getUserID() === $this) {
                $groupMember->setUserID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupRequests[]
     */
    public function getGroupRequests(): Collection
    {
        return $this->groupRequests;
    }

    public function addGroupRequest(GroupRequests $groupRequest): self
    {
        if (!$this->groupRequests->contains($groupRequest)) {
            $this->groupRequests[] = $groupRequest;
            $groupRequest->setUserID($this);
        }

        return $this;
    }

    public function removeGroupRequest(GroupRequests $groupRequest): self
    {
        if ($this->groupRequests->contains($groupRequest)) {
            $this->groupRequests->removeElement($groupRequest);
            // set the owning side to null (unless already changed)
            if ($groupRequest->getUserID() === $this) {
                $groupRequest->setUserID(null);
            }
        }

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
            $applicationAdmin->setUserID($this);
        }

        return $this;
    }

    public function removeApplicationAdmin(ApplicationAdmins $applicationAdmin): self
    {
        if ($this->applicationAdmins->contains($applicationAdmin)) {
            $this->applicationAdmins->removeElement($applicationAdmin);
            // set the owning side to null (unless already changed)
            if ($applicationAdmin->getUserID() === $this) {
                $applicationAdmin->setUserID(null);
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
            $userTransaction->setUserID($this);
        }

        return $this;
    }

    public function removeUserTransaction(UserTransactions $userTransaction): self
    {
        if ($this->userTransactions->contains($userTransaction)) {
            $this->userTransactions->removeElement($userTransaction);
            // set the owning side to null (unless already changed)
            if ($userTransaction->getUserID() === $this) {
                $userTransaction->setUserID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupMembershipNotifications[]
     */
    public function getGroupMembershipNotifications(): Collection
    {
        return $this->groupMembershipNotifications;
    }

    public function addGroupMembershipNotification(GroupMembershipNotifications $groupMembershipNotification): self
    {
        if (!$this->groupMembershipNotifications->contains($groupMembershipNotification)) {
            $this->groupMembershipNotifications[] = $groupMembershipNotification;
            $groupMembershipNotification->setUserID($this);
        }

        return $this;
    }

    public function removeGroupMembershipNotification(GroupMembershipNotifications $groupMembershipNotification): self
    {
        if ($this->groupMembershipNotifications->contains($groupMembershipNotification)) {
            $this->groupMembershipNotifications->removeElement($groupMembershipNotification);
            // set the owning side to null (unless already changed)
            if ($groupMembershipNotification->getUserID() === $this) {
                $groupMembershipNotification->setUserID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupApplicationNotifications[]
     */
    public function getGroupApplicationNotifications(): Collection
    {
        return $this->groupApplicationNotifications;
    }

    public function addGroupApplicationNotification(GroupApplicationNotifications $groupApplicationNotification): self
    {
        if (!$this->groupApplicationNotifications->contains($groupApplicationNotification)) {
            $this->groupApplicationNotifications[] = $groupApplicationNotification;
            $groupApplicationNotification->setUserID($this);
        }

        return $this;
    }

    public function removeGroupApplicationNotification(GroupApplicationNotifications $groupApplicationNotification): self
    {
        if ($this->groupApplicationNotifications->contains($groupApplicationNotification)) {
            $this->groupApplicationNotifications->removeElement($groupApplicationNotification);
            // set the owning side to null (unless already changed)
            if ($groupApplicationNotification->getUserID() === $this) {
                $groupApplicationNotification->setUserID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RecentSimulations[]
     */
    public function getRecentSimulations(): Collection
    {
        return $this->recentSimulations;
    }

    public function addRecentSimulation(RecentSimulations $recentSimulation): self
    {
        if (!$this->recentSimulations->contains($recentSimulation)) {
            $this->recentSimulations[] = $recentSimulation;
            $recentSimulation->setUserID($this);
        }

        return $this;
    }

    public function removeRecentSimulation(RecentSimulations $recentSimulation): self
    {
        if ($this->recentSimulations->contains($recentSimulation)) {
            $this->recentSimulations->removeElement($recentSimulation);
            // set the owning side to null (unless already changed)
            if ($recentSimulation->getUserID() === $this) {
                $recentSimulation->setUserID(null);
            }
        }

        return $this;
    }
}
