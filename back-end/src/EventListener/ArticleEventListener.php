<?php

namespace App\EventListener;

use DateTime;
use DateTimeImmutable;
use App\Entity\Article;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ArticleEventListener
{

    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }


    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Article) {
            return;
        }
        $entity->setCreatedAt(new DateTimeImmutable());
        $entity->setUpdatedAt(new DateTime());
        $entity->setAuthor($this->security->getUser());
        $entity->setStatus('draft');
        if($entity->isPrivate() && !empty($entity->getSharedWith())){
            $entity->setStatus('published');
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Article) {
            return;
        }
        $entity->setUpdatedAt(new DateTime());
    }


}