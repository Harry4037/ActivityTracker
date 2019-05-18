<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupsRepository")
 * @ORM\Table(name="groups")
 */
class Groups
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer",name="groupID")
     */
    private $id;

    /**
     * @var string
     * 
     * @Assert\NotBlank(message = "Please enter a group name.")
     * @ORM\Column(type="string", length=20, nullable=true,name="groupName",options={"default":"Personal"})
     */
    private $groupName;

    /**
     * @var string
     * 
     * @Assert\NotBlank(message = "Please enter a description of your group.")
     * @ORM\Column(type="string", length=80, nullable=true,name="description",options={"default":"This is your private data collection. You are its administrator"})
     */
    private $description;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="groups")
     * @ORM\JoinColumn(name="creatorID", referencedColumnName="userID")
     */
    private $creatorID;

    /**
     * @var integer
     * 
     * @ORM\Column(type="integer", nullable=true,name="publicView",options={"default":"0"})
     */
    private $publicView;

    /**
     * @var integer
     * 
     * @ORM\Column(type="integer", nullable=true,name="approveToJoin",options={"default":"1"})
     */
    private $approveToJoin;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=20, nullable=true,name="rssID")
     */
    private $rssID;

    /**
     * @var /datetime $created_at
     * 
     * @ORM\Column(type="datetime", nullable=true,name="created_at")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity="GroupMembers", mappedBy="groupID", orphanRemoval=true)
     */
    private $groupMembers;

    /**
     * @ORM\OneToMany(targetEntity="GroupRequests", mappedBy="groupID")
     */
    private $groupRequests;

    /**
     * @ORM\OneToMany(targetEntity="UserTransactions", mappedBy="groupID", orphanRemoval=true)
     */
    private $userTransactions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupMembershipNotifications", mappedBy="groupID")
     */
    private $groupMembershipNotifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupApplicationNotifications", mappedBy="groupID")
     */
    private $groupApplicationNotifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecentSimulations", mappedBy="groupID")
     */
    private $recentSimulations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupApplications", mappedBy="groupID")
     */
    private $groupApplications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Archives", mappedBy="groupID")
     */
    private $archives;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CurrentTransactions", mappedBy="groupID")
     */
    private $currentTransactions;

    public function __construct()
    {
        $this->groupMembers = new ArrayCollection();
        $this->groupRequests = new ArrayCollection();
        $this->userTransactions = new ArrayCollection();
        $this->groupMembershipNotifications = new ArrayCollection();
        $this->groupApplicationNotifications = new ArrayCollection();
        $this->recentSimulations = new ArrayCollection();
        $this->groupApplications = new ArrayCollection();
        $this->archives = new ArrayCollection();
        $this->currentTransactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function setGroupName(?string $groupName): self
    {
        $this->groupName = $groupName;

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

    public function getCreatorID(): ?int
    {
        return $this->creatorID;
    }

    public function setCreatorID(?int $creatorID): self
    {
        $this->creatorID = $creatorID;

        return $this;
    }

    public function getPublicView(): ?int
    {
        return $this->publicView;
    }

    public function setPublicView(?int $publicView): self
    {
        $this->publicView = $publicView;

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

    public function getRssID(): ?string
    {
        return $this->rssID;
    }

    public function setRssID(?string $rssID): self
    {
        $this->rssID = $rssID;

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
            $groupMember->setGroupID($this);
        }

        return $this;
    }

    public function removeGroupMember(GroupMembers $groupMember): self
    {
        if ($this->groupMembers->contains($groupMember)) {
            $this->groupMembers->removeElement($groupMember);
            // set the owning side to null (unless already changed)
            if ($groupMember->getGroupID() === $this) {
                $groupMember->setGroupID(null);
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
            $groupRequest->setGroupID($this);
        }

        return $this;
    }

    public function removeGroupRequest(GroupRequests $groupRequest): self
    {
        if ($this->groupRequests->contains($groupRequest)) {
            $this->groupRequests->removeElement($groupRequest);
            // set the owning side to null (unless already changed)
            if ($groupRequest->getGroupID() === $this) {
                $groupRequest->setGroupID(null);
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
            $userTransaction->setGroupID($this);
        }

        return $this;
    }

    public function removeUserTransaction(UserTransactions $userTransaction): self
    {
        if ($this->userTransactions->contains($userTransaction)) {
            $this->userTransactions->removeElement($userTransaction);
            // set the owning side to null (unless already changed)
            if ($userTransaction->getGroupID() === $this) {
                $userTransaction->setGroupID(null);
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
            $groupMembershipNotification->setGroupID($this);
        }

        return $this;
    }

    public function removeGroupMembershipNotification(GroupMembershipNotifications $groupMembershipNotification): self
    {
        if ($this->groupMembershipNotifications->contains($groupMembershipNotification)) {
            $this->groupMembershipNotifications->removeElement($groupMembershipNotification);
            // set the owning side to null (unless already changed)
            if ($groupMembershipNotification->getGroupID() === $this) {
                $groupMembershipNotification->setGroupID(null);
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
            $groupApplicationNotification->setGroupID($this);
        }

        return $this;
    }

    public function removeGroupApplicationNotification(GroupApplicationNotifications $groupApplicationNotification): self
    {
        if ($this->groupApplicationNotifications->contains($groupApplicationNotification)) {
            $this->groupApplicationNotifications->removeElement($groupApplicationNotification);
            // set the owning side to null (unless already changed)
            if ($groupApplicationNotification->getGroupID() === $this) {
                $groupApplicationNotification->setGroupID(null);
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
            $recentSimulation->setGroupID($this);
        }

        return $this;
    }

    public function removeRecentSimulation(RecentSimulations $recentSimulation): self
    {
        if ($this->recentSimulations->contains($recentSimulation)) {
            $this->recentSimulations->removeElement($recentSimulation);
            // set the owning side to null (unless already changed)
            if ($recentSimulation->getGroupID() === $this) {
                $recentSimulation->setGroupID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupApplications[]
     */
    public function getGroupApplications(): Collection
    {
        return $this->groupApplications;
    }

    public function addGroupApplication(GroupApplications $groupApplication): self
    {
        if (!$this->groupApplications->contains($groupApplication)) {
            $this->groupApplications[] = $groupApplication;
            $groupApplication->setGroupID($this);
        }

        return $this;
    }

    public function removeGroupApplication(GroupApplications $groupApplication): self
    {
        if ($this->groupApplications->contains($groupApplication)) {
            $this->groupApplications->removeElement($groupApplication);
            // set the owning side to null (unless already changed)
            if ($groupApplication->getGroupID() === $this) {
                $groupApplication->setGroupID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Archives[]
     */
    public function getArchives(): Collection
    {
        return $this->archives;
    }

    public function addArchive(Archives $archive): self
    {
        if (!$this->archives->contains($archive)) {
            $this->archives[] = $archive;
            $archive->setGroupID($this);
        }

        return $this;
    }

    public function removeArchive(Archives $archive): self
    {
        if ($this->archives->contains($archive)) {
            $this->archives->removeElement($archive);
            // set the owning side to null (unless already changed)
            if ($archive->getGroupID() === $this) {
                $archive->setGroupID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CurrentTransactions[]
     */
    public function getCurrentTransactions(): Collection
    {
        return $this->currentTransactions;
    }

    public function addCurrentTransaction(CurrentTransactions $currentTransaction): self
    {
        if (!$this->currentTransactions->contains($currentTransaction)) {
            $this->currentTransactions[] = $currentTransaction;
            $currentTransaction->setGroupID($this);
        }

        return $this;
    }

    public function removeCurrentTransaction(CurrentTransactions $currentTransaction): self
    {
        if ($this->currentTransactions->contains($currentTransaction)) {
            $this->currentTransactions->removeElement($currentTransaction);
            // set the owning side to null (unless already changed)
            if ($currentTransaction->getGroupID() === $this) {
                $currentTransaction->setGroupID(null);
            }
        }

        return $this;
    }
}
