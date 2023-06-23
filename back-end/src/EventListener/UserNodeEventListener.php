<?php

namespace App\EventListener;

use DateTimeImmutable;
use App\Entity\UserNode;
use Doctrine\Persistence\Event\LifecycleEventArgs;
class UserNodeEventListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof UserNode) {
            return;
        }
        $entity->setCreatedAt(new DateTimeImmutable());
    }

    public function postPersist(LifecycleEventArgs $args): void
    {   
        $entity = $args->getObject();
        if (!$entity instanceof UserNode) {
            return;
        }  
    }

}