<?php

namespace App\MessageHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Message\TriggerAwards;
use App\Service\RewardService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;

class TriggerAwardsHandler implements MessageHandlerInterface
{
    private $entityManager;
    private $rewardService;

    public function __construct(
        EntityManagerInterface $entityManager,
        RewardService $rewardService,
        UserRepository $userRepository,
    )
    {   
        $this->entityManager = $entityManager;
        $this->rewardService = $rewardService;
        $this->userRepository = $userRepository;
    }


    public function __invoke(TriggerAwards $message)
    {

        $user = $message->getUser();
        $user = $this->userRepository->find($user->getId());
        $entity = $message->getEntity();
        
        // Check for achievement triggered by amount of achievements published
        if ($entity instanceof Achievement) {
            $user = $this->rewardService->earnAchievementsByCountOfPublishedAchievements($user);
        }

        // Check for achievement triggered by amount of articles published
        if($entity instanceof Article){
            $user = $this->rewardService->earnAchievementsByCountOfPublishedArticles($user);
        }

        // Check for achievement triggered by amount of invitation accepted
        if($entity instanceof Invitation){
            $user = $this->rewardService->earnAchievementsByCountOfAcceptedInvitations($user);
        }
        
        // Check for achievement triggered by certain amount of experience earned
        $user = $this->rewardService->earnAchievementsByExperience($user);

        // Check if user has unlocked new quests and add it
        $user = $this->rewardService->unlockQuestsByAchievements($user);

        // Check if user has unlocked new stories and add it
        $user = $this->rewardService->unlockStoriesByQuests($user);
    
        // Check if user has unlocked new epics and add it
        $user = $this->rewardService->unlockEpicsByStories($user);

        // check if any user quest is finished, if so, update it and add xp to user
        $user = $this->rewardService->finishQuests($user, $this->entityManager);

        // check if any user story is finished, if so, update it and add xp to user
        $user = $this->rewardService->finishStories($user, $this->entityManager);

        // check if any user epic is finished, if so, update it and add xp to user
        $user = $this->rewardService->finishEpics($user, $this->entityManager);

        // Check if user got a new rank based on his new experience
        $user = $this->rewardService->updateRank($user); 

        // Persist
        $this->entityManager->persist($user);
        $this->entityManager->flush();

    }

}

