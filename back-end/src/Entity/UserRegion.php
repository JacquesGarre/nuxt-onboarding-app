<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRegionRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRegionRepository::class)]
#[ApiResource(paginationEnabled: false, normalizationContext: ['groups' => ['userRegion']])]
#[ApiFilter(SearchFilter::class, properties: ['user' => 'exact'])]
class UserRegion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('userRegion')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userRegions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('userRegion')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userRegions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('userRegion')]
    private ?Region $region = null;

    #[ORM\Column]
    #[Groups('userRegion')]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups('userRegion')]
    public function getName(): ?string
    {
        return $this->region->getName();
    }

    #[Groups('userRegion')]
    public function getGeojson(): ?string
    {
        return $this->region->getGeojson();
    }

    #[Groups('userRegion')]
    public function getCountry(): ?string
    {
        return $this->region->getCountry();
    }

    #[Groups('userRegion')]
    public function getContinent(): ?string
    {
        return $this->region->getCountry()->getContinent();
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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

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
