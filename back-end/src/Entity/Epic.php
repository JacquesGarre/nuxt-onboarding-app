<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EpicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Validator as CustomValidator;
#[ORM\Entity(repositoryClass: EpicRepository::class)]
#[ApiResource]
#[CustomValidator\Constraints\Credit()]
class Epic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $experienceAward = null;

    #[ORM\ManyToOne(inversedBy: 'epics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Story::class, inversedBy: 'epics')]
    private Collection $stories;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToMany(mappedBy: 'epic', targetEntity: UserEpic::class, orphanRemoval: true)]
    private Collection $userEpics;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'epics')]
    private Collection $categories;

    #[ORM\Column]
    private ?bool $sponsored = null;

    #[ORM\Column]
    private ?bool $private = null;

    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $sharedWith;

    public function __construct()
    {
        $this->stories = new ArrayCollection();
        $this->userEpics = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->sharedWith = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->author;
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

    public function getExperienceAward(): ?int
    {
        return $this->experienceAward;
    }

    public function setExperienceAward(?int $experienceAward): self
    {
        $this->experienceAward = $experienceAward;

        return $this;
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

    /**
     * @return Collection<int, Story>
     */
    public function getStories(): Collection
    {
        return $this->stories;
    }

    public function addStory(Story $story): self
    {
        if (!$this->stories->contains($story)) {
            $this->stories->add($story);
        }

        return $this;
    }

    public function removeStory(Story $story): self
    {
        $this->stories->removeElement($story);

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

    /**
     * @return Collection<int, UserEpic>
     */
    public function getUserEpics(): Collection
    {
        return $this->userEpics;
    }

    public function addUserEpic(UserEpic $userEpic): self
    {
        if (!$this->userEpics->contains($userEpic)) {
            $this->userEpics->add($userEpic);
            $userEpic->setEpic($this);
        }

        return $this;
    }

    public function removeUserEpic(UserEpic $userEpic): self
    {
        if ($this->userEpics->removeElement($userEpic)) {
            // set the owning side to null (unless already changed)
            if ($userEpic->getEpic() === $this) {
                $userEpic->setEpic(null);
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
