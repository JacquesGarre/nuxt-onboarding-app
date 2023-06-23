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

    #[ORM\Column]
    private ?bool $getMobileAppSettings = null;

    #[ORM\Column]
    private ?bool $postMobileAppSettings = null;

    #[ORM\Column]
    private ?bool $patchMobileAppSettings = null;

    #[ORM\Column]
    private ?bool $putMobileAppSettings = null;

    #[ORM\Column]
    private ?bool $deleteMobileAppSettings = null;

    #[ORM\Column]
    private ?bool $getArticles = null;

    #[ORM\Column]
    private ?bool $postArticles = null;

    #[ORM\Column]
    private ?bool $patchArticles = null;

    #[ORM\Column]
    private ?bool $putArticles = null;

    #[ORM\Column]
    private ?bool $deleteArticles = null;

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

    public function isGetMobileAppSettings(): ?bool
    {
        return $this->getMobileAppSettings;
    }

    public function setGetMobileAppSettings(bool $getMobileAppSettings): self
    {
        $this->getMobileAppSettings = $getMobileAppSettings;

        return $this;
    }

    public function isPostMobileAppSettings(): ?bool
    {
        return $this->postMobileAppSettings;
    }

    public function setPostMobileAppSettings(bool $postMobileAppSettings): self
    {
        $this->postMobileAppSettings = $postMobileAppSettings;

        return $this;
    }

    public function isPatchMobileAppSettings(): ?bool
    {
        return $this->patchMobileAppSettings;
    }

    public function setPatchMobileAppSettings(bool $patchMobileAppSettings): self
    {
        $this->patchMobileAppSettings = $patchMobileAppSettings;

        return $this;
    }

    public function isPutMobileAppSettings(): ?bool
    {
        return $this->putMobileAppSettings;
    }

    public function setPutMobileAppSettings(bool $putMobileAppSettings): self
    {
        $this->putMobileAppSettings = $putMobileAppSettings;

        return $this;
    }

    public function isDeleteMobileAppSettings(): ?bool
    {
        return $this->deleteMobileAppSettings;
    }

    public function setDeleteMobileAppSettings(bool $deleteMobileAppSettings): self
    {
        $this->deleteMobileAppSettings = $deleteMobileAppSettings;

        return $this;
    }

    public function isGetArticles(): ?bool
    {
        return $this->getArticles;
    }

    public function setGetArticles(bool $getArticles): self
    {
        $this->getArticles = $getArticles;

        return $this;
    }

    public function isPostArticles(): ?bool
    {
        return $this->postArticles;
    }

    public function setPostArticles(bool $postArticles): self
    {
        $this->postArticles = $postArticles;

        return $this;
    }

    public function isPatchArticles(): ?bool
    {
        return $this->patchArticles;
    }

    public function setPatchArticles(bool $patchArticles): self
    {
        $this->patchArticles = $patchArticles;

        return $this;
    }

    public function isPutArticles(): ?bool
    {
        return $this->putArticles;
    }

    public function setPutArticles(bool $putArticles): self
    {
        $this->putArticles = $putArticles;

        return $this;
    }

    public function isDeleteArticles(): ?bool
    {
        return $this->deleteArticles;
    }

    public function setDeleteArticles(bool $deleteArticles): self
    {
        $this->deleteArticles = $deleteArticles;

        return $this;
    }
}
