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
     * @ORM\ManyToOne(targetEntity="Users")
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

    public function __construct()
    {
        $this->groupMembers = new ArrayCollection();
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
}
