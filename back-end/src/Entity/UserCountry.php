<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserCountryRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserCountryRepository::class)]
#[ApiResource(paginationEnabled: false, normalizationContext: ['groups' => ['userCountry']])]
#[ApiFilter(SearchFilter::class, properties: ['user' => 'exact'])]
class UserCountry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('userCountry')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userCountries')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('userCountry')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userCountries')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('userCountry')]
    private ?Country $country = null;

    #[ORM\Column]
    #[Groups('userCountry')]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups('userCountry')]
    public function getName(): string
    {
        return $this->country->getName();
    }

    #[Groups('userCountry')]
    public function getGeojson(): string
    {
        return $this->country->getGeojson();
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

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

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
