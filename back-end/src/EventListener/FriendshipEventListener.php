<?php

namespace App\EventListener;


use DateTimeImmutable;
use App\Entity\Friendship;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

class FriendshipEventListener
{

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Friendship) {
            return;
        }
        $entity->setCreatedAt(new DateTimeImmutable());
    }


}