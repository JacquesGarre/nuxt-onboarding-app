<?php

namespace App\Entity;


use App\Repository\ApiTokenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApiTokenRepository::class)]
class ApiToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column]
    private ?bool $getUsers = null;

    #[ORM\Column]
    private ?bool $postUsers = null;

    #[ORM\Column]
    private ?bool $patchUsers = null;

    #[ORM\Column]
    private ?bool $putUsers = null;

    #[ORM\Column]
    private ?bool $deleteUsers = null;


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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function isGetUsers(): ?bool
    {
        return $this->getUsers;
    }

    public function setGetUsers(bool $getUsers): self
    {
        $this->getUsers = $getUsers;

        return $this;
    }

    public function isPostUsers(): ?bool
    {
        return $this->postUsers;
    }

    public function setPostUsers(bool $postUsers): self
    {
        $this->postUsers = $postUsers;

        return $this;
    }

    public function isPatchUsers(): ?bool
    {
        return $this->patchUsers;
    }

    public function setPatchUsers(bool $patchUsers): self
    {
        $this->patchUsers = $patchUsers;

        return $this;
    }

    public function isPutUsers(): ?bool
    {
        return $this->putUsers;
    }

    public function setPutUsers(bool $putUsers): self
    {
        $this->putUsers = $putUsers;

        return $this;
    }

    public function isDeleteUsers(): ?bool
    {
        return $this->deleteUsers;
    }

    public function setDeleteUsers(bool $deleteUsers): self
    {
        $this->deleteUsers = $deleteUsers;

        return $this;
    }

}
