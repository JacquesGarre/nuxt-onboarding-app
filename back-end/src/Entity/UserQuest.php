<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserQuestRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity(repositoryClass: UserQuestRepository::class)]
#[ApiResource]
class UserQuest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userQuests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userQuests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quest $quest = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endedAt = null;

    #[ORM\Column]
    private ?int $experienceEarned = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function isCompleted(): bool
    {

        $achievementsNeeded = $this->getQuest()->getAchievements();
        $userAchievementsRelatedToQuest = $this->getUser()->getUserAchievements($achievementsNeeded);
        $userAchievementsIDS = array_map(function($userAchievement){
            return $userAchievement->getAchievement()->getId();
        }, iterator_to_array($userAchievementsRelatedToQuest));

        // Does every achievement needed has been earned by user?
        foreach($achievementsNeeded as $achievementNeeded){
            if(!in_array($achievementNeeded->getId(), $userAchievementsIDS)){
                return false;
            }
        }

        return true;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->quest->getTitle();
    }

    public function getDescription(): ?string
    {
        return $this->quest->getDescription();
    }

    public function isTemporary(): bool
    {
        return $this->quest->isTemporary();
    }

    public function getQuest(): ?Quest
    {
        return $this->quest;
    }

    public function setQuest(?Quest $quest): self
    {
        $this->quest = $quest;

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

    public function setExperienceEarned(int $experienceEarned): self
    {
        $this->experienceEarned = $experienceEarned;

        return $this;
    }
}
