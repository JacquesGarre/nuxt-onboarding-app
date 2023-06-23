<?php

namespace App\EventListener;

use DateTime;
use DateTimeImmutable;
use App\Entity\Place;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\SecurityBundle\Security;

class PlaceEventListener
{

    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }


    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Place) {
            return;
        }
        $entity->setCreatedAt(new DateTimeImmutable());
        $entity->setAuthor($this->security->getUser());
        $entity->setStatus('pending');
        if($entity->isPrivate() && !empty($entity->getSharedWith())){
            $entity->setStatus('active');
        }
    }

}