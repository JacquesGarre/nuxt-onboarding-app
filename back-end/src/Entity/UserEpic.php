<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserEpicRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserEpicRepository::class)]
#[ApiResource]
class UserEpic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userEpics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userEpics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Epic $epic = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $experienceEarned = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->epic->getTitle();
    }

    public function getDescription(): ?string
    {
        return $this->epic->getDescription();
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

    public function getEpic(): ?Epic
    {
        return $this->epic;
    }

    public function setEpic(?Epic $epic): self
    {
        $this->epic = $epic;

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

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeImmutable $startedAt): self
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
        $storiesNeeded = $this->getEpic()->getStories();
        $userStoriesFinishedRelatedToEpic = $this->getUser()->getUserStories($storiesNeeded)->filter(function(UserStory $userStory){
            return $userStory->getStatus() == 'finished';
        });
        return count($userStoriesFinishedRelatedToEpic) == count($storiesNeeded);
    }

}
