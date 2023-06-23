<?php

namespace App\State;

use App\Entity\UserNode;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\UserNodeRepository;

class UserNodeProcessor implements ProcessorInterface
{
    private $userNodeRepository;

    public function __construct(
        private ProcessorInterface $persistProcessor,
        UserNodeRepository $userNodeRepository
    )
    {
        $this->userNodeRepository = $userNodeRepository;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if($data instanceof UserNode){

            $existingNode = $this->userNodeRepository->findOneBy([
                'user' => $data->getUser(),
                'longitude' => $data->getLongitude(),
                'latitude' => $data->getLatitude()
            ]);

            // If node exist for this user, increment passage and update existing one
            if(!empty($existingNode)){
                $passageCount = $existingNode->getPassageCount() + 1;
                $existingNode->setPassageCount($passageCount);
                $data = $existingNode;
            } 

            $this->persistProcessor->process($data, $operation, $uriVariables, $context);
            
        }

        return $data;
   
    }
}
