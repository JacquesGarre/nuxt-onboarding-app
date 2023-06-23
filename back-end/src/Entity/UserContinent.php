<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserContinentRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserContinentRepository::class)]
#[ApiResource(paginationEnabled: false, normalizationContext: ['groups' => ['userContinent']])]
#[ApiFilter(SearchFilter::class, properties: ['user' => 'exact'])]
class UserContinent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('userContinent')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userContinents')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('userContinent')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userContinents')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('userContinent')]
    private ?Continent $continent = null;

    #[ORM\Column]
    #[Groups('userContinent')]
    private ?\DateTimeImmutable $createdAt = null;

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

    #[Groups('userContinent')]
    public function getName(): ?String
    {
        return $this->continent->getName();
    }

    #[Groups('userContinent')]
    public function getGeojson(): ?String
    {
        return $this->continent->getGeojson();
    }

    public function getContinent(): ?Continent
    {
        return $this->continent;
    }

    public function setContinent(?Continent $continent): self
    {
        $this->continent = $continent;

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
