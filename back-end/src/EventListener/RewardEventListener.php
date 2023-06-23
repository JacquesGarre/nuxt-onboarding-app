<?php

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Service\RewardService;

class RewardEventListener
{

    private $rewardService;

    public function __construct(RewardService $rewardService)
    {
        $this->rewardService = $rewardService;
    }

    public function postPersist(LifecycleEventArgs $args): void
    {   
        $entity = $args->getObject();
        $user = method_exists($entity, 'getOwner') ? $entity->getOwner() : null;
        $entityManager = $args->getObjectManager();

        // Check for awards after creating any entity
        $this->rewardService->postPersistRewards($user, $entity, $entityManager);


    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $user = method_exists($entity, 'getOwner') ? $entity->getOwner() : null;
        $entityManager = $args->getObjectManager();

        // Check for awards after updating any entity
        $this->rewardService->postUpdateRewards($user, $entity, $entityManager);
    }



}