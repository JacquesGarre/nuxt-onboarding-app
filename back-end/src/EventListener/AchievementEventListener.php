<?php

namespace App\EventListener;

use DateTime;
use DateTimeImmutable;
use App\Entity\Achievement;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

class AchievementEventListener
{

    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Achievement) {
            return;
        }
        $entity->setCreatedAt(new DateTimeImmutable());
        $entity->setUpdatedAt(new DateTime());
        $entity->setAuthor($this->security->getUser());
        
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Achievement) {
            return;
        }
        $entity->setUpdatedAt(new DateTime());
    }


}