<?php

namespace App\EventListener;

use DateTimeImmutable;
use App\Entity\Feedback;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

class FeedbackEventListener
{

    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Feedback) {
            return;
        }
        $entity->setCreatedAt(new DateTimeImmutable());
        $entity->setAuthor($this->security->getUser());
    }


}