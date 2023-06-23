<?php

namespace App\Message;

use App\Entity\UserNode;

class FindAchievementsAroundUserNode
{
    private $userNode;

    public function __construct(UserNode $userNode)
    {
        $this->userNode = $userNode;
    }

    public function getUserNode(): UserNode
    {
        return $this->userNode;
    }
}