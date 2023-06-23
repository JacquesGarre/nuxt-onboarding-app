<?php

namespace App\EventListener;

use DateTime;
use DateTimeImmutable;
use App\Entity\Epic;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

class EpicEventListener
{

    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Epic) {
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
        if (!$entity instanceof Epic) {
            return;
        }
        $entity->setUpdatedAt(new DateTime());
    }


}