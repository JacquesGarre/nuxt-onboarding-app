<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use App\Filter\RadiusFilter;
use ApiPlatform\Core\Annotation\ApiFilter;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
#[ApiResource(paginationEnabled: false, filters:[RadiusFilter::class])]

class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'countries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Continent $continent = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $geojson = null;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: UserCountry::class, orphanRemoval: true)]
    private Collection $userCountries;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Region::class)]
    private Collection $regions;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $longitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $latitude = null;

    public function __construct()
    {
        $this->userCountries = new ArrayCollection();
        $this->regions = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
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

    public function getContinent(): ?Continent
    {
        return $this->continent;
    }

    public function setContinent(?Continent $continent): self
    {
        $this->continent = $continent;

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
     * @return Collection<int, UserCountry>
     */
    public function getUserCountries(): Collection
    {
        return $this->userCountries;
    }

    public function addUserCountry(UserCountry $userCountry): self
    {
        if (!$this->userCountries->contains($userCountry)) {
            $this->userCountries->add($userCountry);
            $userCountry->setCountry($this);
        }

        return $this;
    }

    public function removeUserCountry(UserCountry $userCountry): self
    {
        if ($this->userCountries->removeElement($userCountry)) {
            // set the owning side to null (unless already changed)
            if ($userCountry->getCountry() === $this) {
                $userCountry->setCountry(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, Region>
     */
    public function getRegions(): Collection
    {
        return $this->regions;
    }

    public function addRegion(Region $region): self
    {
        if (!$this->regions->contains($region)) {
            $this->regions->add($region);
            $region->setCountry($this);
        }

        return $this;
    }

    public function removeRegion(Region $region): self
    {
        if ($this->regions->removeElement($region)) {
            // set the owning side to null (unless already changed)
            if ($region->getCountry() === $this) {
                $region->setCountry(null);
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
