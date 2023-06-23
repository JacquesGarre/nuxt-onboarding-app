<?php

namespace App\EventListener;

use DateTime;
use DateTimeImmutable;
use App\Entity\Subscription;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
class SubscriptionEventListener
{

    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
       $this->security = $security;
       $this->entityManager = $entityManager;
    }  

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Subscription) {
            return;
        }
        $entityManager = $args->getObjectManager();

        $pack = $entity->getPack();
        $user = $entity->getUser();

        $entity->setCreatedAt(new DateTimeImmutable());
        $entity->setUpdatedAt(new DateTime());
        $entity->setStartsAt(new DateTimeImmutable());
        if($pack->getDuration() !== 'unlimited'){
            $entity->setEndsAt(new DateTimeImmutable($pack->getDuration()));
        }
        $entity->setCredited(false);

        if($entity->getStatus() == 'active' && !$entity->isCredited()){
            switch($pack->getType()){
                case 'premium':
                    $user->setNbPrivateArticlesLeft($user->getNbPrivateArticlesLeft() + $pack->getNbArticles());
                    $user->setNbPrivateAchievementsLeft($user->getNbPrivateAchievementsLeft() + $pack->getNbAchievements());
                    $user->setNbPrivateQuestsLeft($user->getNbPrivateQuestsLeft() + $pack->getNbQuests());
                    $user->setNbPrivateStoriesLeft($user->getNbPrivateStoriesLeft() + $pack->getNbStories());
                    $user->setNbPrivateEpicsLeft($user->getNbPrivateEpicsLeft() + $pack->getNbEpics());
                    $user->setNbPrivatePlacesLeft($user->getNbPrivatePlacesLeft() + $pack->getNbPlaces());
                break;
                case 'partner':
                    $user->setNbSponsoredArticlesLeft($user->getNbSponsoredArticlesLeft() + $pack->getNbArticles());
                    $user->setNbSponsoredAchievementsLeft($user->getNbSponsoredAchievementsLeft() + $pack->getNbAchievements());
                    $user->setNbSponsoredQuestsLeft($user->getNbSponsoredQuestsLeft() + $pack->getNbQuests());
                    $user->setNbSponsoredStoriesLeft($user->getNbSponsoredStoriesLeft() + $pack->getNbStories());
                    $user->setNbSponsoredEpicsLeft($user->getNbSponsoredEpicsLeft() + $pack->getNbEpics());
                    $user->setNbSponsoredPlacesLeft($user->getNbSponsoredPlacesLeft() + $pack->getNbPlaces());
                break;
            }
            $entityManager->persist($user);
            $entityManager->flush();
            $entity->setCredited(true);
        }
    }


    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Subscription) {
            return;
        }
        $entity->setUpdatedAt(new DateTime());

    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        if (!$entity instanceof Subscription) {
            return;
        }

        $pack = $entity->getPack();
        $user = $entity->getUser();

        if($entity->getStatus() == 'active' && !$entity->isCredited()){
            $entity->setCredited(true);
            switch($pack->getType()){
                case 'premium':
                    $user->setNbPrivateArticlesLeft($user->getNbPrivateArticlesLeft() + $pack->getNbArticles());
                    $user->setNbPrivateAchievementsLeft($user->getNbPrivateAchievementsLeft() + $pack->getNbAchievements());
                    $user->setNbPrivateQuestsLeft($user->getNbPrivateQuestsLeft() + $pack->getNbQuests());
                    $user->setNbPrivateStoriesLeft($user->getNbPrivateStoriesLeft() + $pack->getNbStories());
                    $user->setNbPrivateEpicsLeft($user->getNbPrivateEpicsLeft() + $pack->getNbEpics());
                    $user->setNbPrivatePlacesLeft($user->getNbPrivatePlacesLeft() + $pack->getNbPlaces());
                break;
                case 'partner':
                    $user->setNbSponsoredArticlesLeft($user->getNbSponsoredArticlesLeft() + $pack->getNbArticles());
                    $user->setNbSponsoredAchievementsLeft($user->getNbSponsoredAchievementsLeft() + $pack->getNbAchievements());
                    $user->setNbSponsoredQuestsLeft($user->getNbSponsoredQuestsLeft() + $pack->getNbQuests());
                    $user->setNbSponsoredStoriesLeft($user->getNbSponsoredStoriesLeft() + $pack->getNbStories());
                    $user->setNbSponsoredEpicsLeft($user->getNbSponsoredEpicsLeft() + $pack->getNbEpics());
                    $user->setNbSponsoredPlacesLeft($user->getNbSponsoredPlacesLeft() + $pack->getNbPlaces());
                break;
            }
            $entityManager->persist($user);
            $entityManager->flush();
        }

    }


}