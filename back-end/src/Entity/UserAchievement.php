<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserAchievementRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Filter\RadiusFilter;

#[ORM\Entity(repositoryClass: UserAchievementRepository::class)]
#[ApiResource(paginationEnabled: false)]
#[ApiFilter(SearchFilter::class, properties: ['user' => 'exact'])]
class UserAchievement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userAchievements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userAchievements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('user')]
    private ?Achievement $achievement = null;

    #[ORM\Column]
    #[Groups('user')]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    #[Groups('user')]
    public function getName(): ?string
    {
        return $this->getAchievement()->getName();
    }

    #[Groups('user')]
    public function getLatitude(): ?string
    {
        return $this->getAchievement()->getLatitude();
    }

    #[Groups('user')]
    public function getLongitude(): ?string
    {
        return $this->getAchievement()->getLongitude();
    }

    #[Groups('user')]
    public function getDescription(): ?string
    {
        return $this->getAchievement()->getDescription();
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAchievement(): ?Achievement
    {
        return $this->achievement;
    }

    public function setAchievement(?Achievement $achievement): self
    {
        $this->achievement = $achievement;

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
}
