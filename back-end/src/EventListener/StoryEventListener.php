<?php

namespace App\EventListener;

use DateTime;
use DateTimeImmutable;
use App\Entity\Story;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

class StoryEventListener
{

    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Story) {
            return;
        }
        $entity->setCreatedAt(new DateTimeImmutable());
        $entity->setUpdatedAt(new DateTime());
        $entity->setAuthor($this->security->getUser());
        $entity->setStatus('draft');
        if($entity->isPrivate() && !empty($entity->getSharedWith())){
            $entity->setStatus('active');
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Story) {
            return;
        }
        $entity->setUpdatedAt(new DateTime());
    }


}