<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ContinentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


use App\Filter\RadiusFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ContinentRepository::class)]
#[ApiResource(
    paginationEnabled: false, 
    filters:[RadiusFilter::class],
    normalizationContext:  ['groups' => ['continent:collection:get']],
)]
#[Get]
#[Put(normalizationContext: ['groups' => ['continent:collection:put']])]
#[Post(normalizationContext: ['groups' => ['continent:collection:post']])]
class Continent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['continent:collection:get'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['continent:collection:get'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['continent:collection:get'])]
    private ?string $geojson = null;

    #[ORM\OneToMany(mappedBy: 'continent', targetEntity: UserContinent::class, orphanRemoval: true)]
    #[Groups(['continent:collection:get', 'continent:collection:post', 'continent:collection:put'])]
    private Collection $userContinents;

    #[ORM\OneToMany(mappedBy: 'continent', targetEntity: Country::class)]
    #[Groups(['continent:collection:get', 'continent:collection:post', 'continent:collection:put'])]
    private Collection $countries;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['continent:collection:get'])]
    private ?string $longitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['continent:collection:get'])]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)] 
    #[Groups(['continent:collection:post','continent:collection:put'])]
    private ?string $wkt = null;

    public function __construct()
    {
        $this->userContinents = new ArrayCollection();
        $this->countries = new ArrayCollection();
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
     * @return Collection<int, UserContinent>
     */
    public function getUserContinents(): Collection
    {
        return $this->userContinents;
    }

    public function addUserContinent(UserContinent $userContinent): self
    {
        if (!$this->userContinents->contains($userContinent)) {
            $this->userContinents->add($userContinent);
            $userContinent->setContinent($this);
        }

        return $this;
    }

    public function removeUserContinent(UserContinent $userContinent): self
    {
        if ($this->userContinents->removeElement($userContinent)) {
            // set the owning side to null (unless already changed)
            if ($userContinent->getContinent() === $this) {
                $userContinent->setContinent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Country>
     */
    public function getCountries(): Collection
    {
        return $this->countries;
    }

    public function addCountry(Country $country): self
    {
        if (!$this->countries->contains($country)) {
            $this->countries->add($country);
            $country->setContinent($this);
        }

        return $this;
    }

    public function removeCountry(Country $country): self
    {
        if ($this->countries->removeElement($country)) {
            // set the owning side to null (unless already changed)
            if ($country->getContinent() === $this) {
                $country->setContinent(null);
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

    public function getWkt(): ?string
    {
        return $this->wkt;
    }

    public function setWkt(?string $wkt): self
    {
        $this->wkt = $wkt;

        return $this;
    }
}
