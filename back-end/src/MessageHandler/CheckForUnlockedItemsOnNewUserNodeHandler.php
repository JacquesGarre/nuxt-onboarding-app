<?php

namespace App\MessageHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Message\CheckForUnlockedItemsOnNewUserNode;
use App\Service\RewardService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\UserNodeRepository;

class CheckForUnlockedItemsOnNewUserNodeHandler implements MessageHandlerInterface
{
    private $entityManager;
    private $rewardService;
    private $userRepository;
    private $userNodeRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        RewardService $rewardService,
        UserRepository $userRepository,
        UserNodeRepository $userNodeRepository
    )
    {   
        $this->entityManager = $entityManager;
        $this->rewardService = $rewardService;
        $this->userRepository = $userRepository;
        $this->userNodeRepository = $userNodeRepository;
    }


    public function __invoke(CheckForUnlockedItemsOnNewUserNode $message)
    {

        $user = $message->getUser();
        $user = $this->userRepository->find($user->getId());
        $userNode = $message->getUserNode();
        $userNode = $this->userNodeRepository->find($userNode->getId());
        
        // Check for achievement triggered by entering a perimeter
        $user = $this->rewardService->earnAchievementsByPerimeter($user, $userNode, $this->entityManager);

        // Check for a new continent unlocked
        [$user, $continent] = $this->rewardService->unlockContinent($user, $userNode, $this->entityManager);

        // Check for a new country unlocked
        if(!empty($continent)){
            [$user, $country] = $this->rewardService->unlockCountry($user, $userNode, $this->entityManager, $continent);
        }

        // Check for a new region unlocked
        if(!empty($country)){
            $user = $this->rewardService->unlockRegion($user, $userNode, $this->entityManager, $country);
        }

        // Persist
        $this->entityManager->persist($user);
        $this->entityManager->flush();

    }

}