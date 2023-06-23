<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AchievementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Validator as CustomValidator;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Filter\RadiusFilter;
use ApiPlatform\Core\Annotation\ApiFilter;

#[ORM\Entity(repositoryClass: AchievementRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['achievement']], paginationEnabled: false, filters:[RadiusFilter::class])]
#[CustomValidator\Constraints\Credit()]
class Achievement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user', 'achievement'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user', 'achievement'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $triggerOn = null;

    #[ORM\Column(nullable: true)]
    private ?int $experience = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user', 'achievement'])]
    private ?string $longitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user', 'achievement'])]
    private ?string $latitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $radius = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $author = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $action = null;

    #[ORM\Column(nullable: true)]
    private ?int $actionCount = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['user', 'achievement'])]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Quest::class, mappedBy: 'achievements')]
    private Collection $quests;

    #[ORM\OneToMany(mappedBy: 'achievement', targetEntity: UserAchievement::class, orphanRemoval: true)]
    private Collection $userAchievements;

    #[ORM\ManyToMany(
        targetEntity: Category::class, 
        inversedBy: 'achievements'
    )]
    #[Groups(['user', 'achievement'])]
    private Collection $categories;

    #[ORM\Column]
    private ?bool $sponsored = null;

    #[ORM\Column]
    private ?bool $private = null;

    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $sharedWith;

    public function __construct()
    {
        $this->quests = new ArrayCollection();
        $this->userAchievements = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->sharedWith = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTriggerOn(): ?string
    {
        return $this->triggerOn;
    }

    public function setTriggerOn(string $triggerOn): self
    {
        $this->triggerOn = $triggerOn;

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(?int $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getRadius(): ?string
    {
        return $this->radius;
    }

    public function setRadius(?string $radius): self
    {
        $this->radius = $radius;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->author;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getActionCount(): ?int
    {
        return $this->actionCount;
    }

    public function setActionCount(?int $actionCount): self
    {
        $this->actionCount = $actionCount;

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

    /**
     * @return Collection<int, Quest>
     */
    public function getQuests(): Collection
    {
        return $this->quests;
    }

    public function addQuest(Quest $quest): self
    {
        if (!$this->quests->contains($quest)) {
            $this->quests->add($quest);
            $quest->addAchievement($this);
        }

        return $this;
    }

    public function removeQuest(Quest $quest): self
    {
        if ($this->quests->removeElement($quest)) {
            $quest->removeAchievement($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, UserAchievement>
     */
    public function getUserAchievements(): Collection
    {
        return $this->userAchievements;
    }

    public function addUserAchievement(UserAchievement $userAchievement): self
    {
        if (!$this->userAchievements->contains($userAchievement)) {
            $this->userAchievements->add($userAchievement);
            $userAchievement->setAchievement($this);
        }

        return $this;
    }

    public function removeUserAchievement(UserAchievement $userAchievement): self
    {
        if ($this->userAchievements->removeElement($userAchievement)) {
            // set the owning side to null (unless already changed)
            if ($userAchievement->getAchievement() === $this) {
                $userAchievement->setAchievement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function isSponsored(): ?bool
    {
        return $this->sponsored;
    }

    public function setSponsored(bool $sponsored): self
    {
        $this->sponsored = $sponsored;

        return $this;
    }

    public function isPrivate(): ?bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): self
    {
        $this->private = $private;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSharedWith(): Collection
    {
        return $this->sharedWith;
    }

    public function addSharedWith(User $sharedWith): self
    {
        if (!$this->sharedWith->contains($sharedWith)) {
            $this->sharedWith->add($sharedWith);
        }

        return $this;
    }

    public function removeSharedWith(User $sharedWith): self
    {
        $this->sharedWith->removeElement($sharedWith);

        return $this;
    }
}
