<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserNodeRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\State\UserNodeProcessor;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Filter\RadiusFilter;

#[ORM\Entity(repositoryClass: UserNodeRepository::class)]
#[ApiResource(
    paginationEnabled: false, 
    filters:[RadiusFilter::class],
    operations: [
        new Get(uriTemplate: 'user_nodes/{id}'),
        new GetCollection(uriTemplate: 'user_nodes'),
        new Post(processor: UserNodeProcessor::class, uriTemplate: 'user_nodes')
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['user' => 'exact'])]

class UserNode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $longitude = null;

    #[ORM\Column(length: 255)]
    private ?string $latitude = null;

    #[ORM\ManyToOne(inversedBy: 'userNodes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?int $passageCount = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->user;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPassageCount(): ?int
    {
        return $this->passageCount;
    }

    public function setPassageCount(int $passageCount): self
    {
        $this->passageCount = $passageCount;

        return $this;
    }
}
