<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Core\Annotation\ApiFilter;
use App\Filter\RadiusFilter;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
#[ApiResource(paginationEnabled: false, filters:[RadiusFilter::class])]

class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'regions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Country $country = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $geojson = null;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: UserRegion::class, orphanRemoval: true)]
    private Collection $userRegions;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $longitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $latitude = null;

    public function __construct()
    {
        $this->userRegions = new ArrayCollection();
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

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getGeojson(): ?string
    {
        return $this->geojson;
    }

    public function setGeojson(string $geojson): self
    {
        $this->geojson = $geojson;

        return $this;
    }

    /**
     * @return Collection<int, UserRegion>
     */
    public function getUserRegions(): Collection
    {
        return $this->userRegions;
    }

    public function addUserRegion(UserRegion $userRegion): self
    {
        if (!$this->userRegions->contains($userRegion)) {
            $this->userRegions->add($userRegion);
            $userRegion->setRegion($this);
        }

        return $this;
    }

    public function removeUserRegion(UserRegion $userRegion): self
    {
        if ($this->userRegions->removeElement($userRegion)) {
            // set the owning side to null (unless already changed)
            if ($userRegion->getRegion() === $this) {
                $userRegion->setRegion(null);
            }
        }

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
}
