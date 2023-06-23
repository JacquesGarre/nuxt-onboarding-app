<?php

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Service\CreditService;

class CreditEventListener
{   
    
    private $creditService;

    public function __construct(CreditService $creditService)
    {
        $this->creditService = $creditService;
    }

    public function postPersist(LifecycleEventArgs $args): void
    {   
        $entity = $args->getObject();
        $user = method_exists($entity, 'getOwner') ? $entity->getOwner() : null;
        $entityManager = $args->getObjectManager();

        // Decrement credit is entity is affected by credit
        $this->creditService->postPersistCredit($user, $entity, $entityManager);
    }


}