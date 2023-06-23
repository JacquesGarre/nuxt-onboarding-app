<?php

namespace App\EventListener;

use DateTime;
use DateTimeImmutable;
use App\Entity\ApiToken;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiTokenEventListener
{

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof ApiToken) {
            return;
        }
        $entity->setCreatedAt(new DateTimeImmutable());
        $entity->setUpdatedAt(new DateTime());
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof ApiToken) {
            return;
        }
        $entity->setUpdatedAt(new DateTime());
    }


}