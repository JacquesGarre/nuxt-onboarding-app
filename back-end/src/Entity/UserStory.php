<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserStoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserStoryRepository::class)]
#[ApiResource]
class UserStory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userStories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userStories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Story $story = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $experienceEarned = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStory(): ?Story
    {
        return $this->story;
    }

    public function setStory(?Story $story): self
    {
        $this->story = $story;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->story->getTitle();
    }

    public function getDescription(): ?string
    {
        return $this->story->getDescription();
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

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeImmutable $endedAt): self
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getExperienceEarned(): ?int
    {
        return $this->experienceEarned;
    }

    public function setExperienceEarned(?int $experienceEarned): self
    {
        $this->experienceEarned = $experienceEarned;

        return $this;
    }

    public function isCompleted(): bool
    {
        $questsNeeded = $this->getStory()->getQuests();
        $userQuestsFinishedRelatedToStory = $this->getUser()->getUserQuests($questsNeeded)->filter(function(UserQuest $userQuest){
            return $userQuest->getStatus() == 'finished';
        });
        return count($userQuestsFinishedRelatedToStory) == count($questsNeeded);
    }


}
