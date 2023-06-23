<?php

namespace App\MessageHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Message\EarnExperience;
use App\Service\RewardService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;

class EarnExperienceHandler implements MessageHandlerInterface
{
    private $entityManager;
    private $rewardService;
    private $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        RewardService $rewardService,
        UserRepository $userRepository
    )
    {   
        $this->entityManager = $entityManager;
        $this->rewardService = $rewardService;
        $this->userRepository = $userRepository;
    }


    public function __invoke(EarnExperience $message)
    {
        $user = $message->getUser();
        $user = $this->userRepository->find($user->getId());
        $entity = $message->getEntity();

        // Earn by experience by action (article created, achievement created, new user node added, new comment, new like)
        $user = $this->rewardService->earnExperienceByAction($user, $entity); 

        // Persist
        $this->entityManager->persist($user);
        $this->entityManager->flush();

    }

}


