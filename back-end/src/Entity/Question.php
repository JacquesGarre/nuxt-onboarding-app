<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ApiResource]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'question_id', targetEntity: UserFeedback::class)]
    private Collection $userFeedback;

    public function __construct()
    {
        $this->userFeedback = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, UserFeedback>
     */
    public function getUserFeedback(): Collection
    {
        return $this->userFeedback;
    }

    public function addUserFeedback(UserFeedback $userFeedback): static
    {
        if (!$this->userFeedback->contains($userFeedback)) {
            $this->userFeedback->add($userFeedback);
            $userFeedback->setQuestionId($this);
        }

        return $this;
    }

    public function removeUserFeedback(UserFeedback $userFeedback): static
    {
        if ($this->userFeedback->removeElement($userFeedback)) {
            // set the owning side to null (unless already changed)
            if ($userFeedback->getQuestionId() === $this) {
                $userFeedback->setQuestionId(null);
            }
        }

        return $this;
    }
}
