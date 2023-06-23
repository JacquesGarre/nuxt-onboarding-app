<?php

namespace App\Message;
use App\Entity\User;

class EarnExperience
{
    private $user;
    private $entity;

    public function __construct(mixed $entity, User $user)
    {   
        $this->user = $user;
        $this->entity = $entity;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getEntity(): mixed
    {
        return $this->entity;
    }

}