<?php

namespace App\Message;

use App\Entity\User;
use App\Entity\UserNode;

class CheckForUnlockedItemsOnNewUserNode
{
    private $userNode;
    private $user;

    public function __construct(UserNode $userNode, User $user)
    {
        $this->userNode = $userNode;
        $this->user = $user;
    }

    public function getUserNode(): UserNode
    {
        return $this->userNode;
    }

    public function getUser(): User
    {
        return $this->user;
    }

}