<?php

namespace App\EventListener;

use DateTime;
use DateTimeImmutable;
use App\Entity\Like;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

class LikeEventListener
{

    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Like) {
            return;
        }
        $entity->setCreatedAt(new DateTimeImmutable());
    }




}