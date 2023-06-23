<?php

namespace App\MessageHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Message\FindAchievementsAroundUserNode;
use App\Service\RewardService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\UserNodeRepository;
use App\Repository\AchievementRepository;
use App\Repository\CategoryRepository;
use App\Service\OpenTripApiService;
use App\Entity\Achievement;
use App\Entity\Category;


class FindAchievementsAroundUserNodeHandler implements MessageHandlerInterface
{
    private $userRepository;
    private $userNodeRepository;
    private $achievementRepository;
    private $categoryRepository;
    private $entityManager;
    private $apiService;

    public function __construct(
        UserRepository $userRepository, 
        UserNodeRepository $userNodeRepository, 
        AchievementRepository $achievementRepository,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager,
        OpenTripApiService $apiService,
    )
    {
        $this->userRepository = $userRepository;
        $this->userNodeRepository = $userNodeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->achievementRepository = $achievementRepository;
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
    }


    public function __invoke(FindAchievementsAroundUserNode $message)
    {

        $userNode = $message->getUserNode();
        $userNode = $this->userNodeRepository->find($userNode->getId());
        $user = $this->userRepository->find($userNode->getUser()->getId());

        // Fetch existing achievement around user node
        $existingAchievements = $this->achievementRepository->findAchievementsByRadiusAroundPoint(
            $userNode->getLatitude(), 
            $userNode->getLongitude(), 
            $_ENV['RADIUS_TO_FIND_PLACES'] * 2
        );

        // Fetch existing categories
        $categories = $this->categoryRepository->findAll();

        // Fetch what is around
        $features = $this->apiService->getPlacesAroundUserNode($userNode); 

        foreach($features as $feature){

            if($feature['geometry']['type'] !== 'Point'){
                continue;
            }
            
            $long = (string) $feature['geometry']['coordinates'][0];
            $lat = (string) $feature['geometry']['coordinates'][1];
            $name = $feature['properties']['name'];
            $achievementCategories = explode(',', $feature['properties']['kinds']);
            $similarAchievements = array_filter($existingAchievements, function($achievement) use($long, $lat, $name) {
                return ($achievement->getLongitude() == $long && $achievement->getLatitude() == $lat) || $name == $achievement->getName();
            });
            if(!empty($similarAchievements) || empty($name) || empty($long) || empty($lat)){
                continue;
            }

            $description = '';
            $xid = $feature['properties']['xid'];
            if(!empty($xid)){
                $description = $this->apiService->getItemDescription($xid); 
                sleep(1);
            }
            
            // else prepare it
            $achievement = new Achievement();
            $achievement->setName($name);
            $achievement->setLongitude($long);
            $achievement->setLatitude($lat);
            $achievement->setTriggerOn('in_perimeter');
            $achievement->setRadius('50');
            $achievement->setStatus('active');
            $achievement->setDescription($description);
            $achievement->setSponsored(false);
            $achievement->setPrivate(false);                   
            foreach($achievementCategories as $achievementCategory){
                $existingCategories = array_filter($categories, function($category) use($achievementCategory) {
                    return $category->getName() == $achievementCategory;
                });
                if(!empty($existingCategories)){
                    $newCategory = reset($existingCategories);
                } else {
                    $newCategory = new Category();
                    $newCategory->setName($achievementCategory);
                    $categories[] = $newCategory;
                    $this->entityManager->persist($newCategory);
                    $this->entityManager->flush();
                }
                $achievement->addCategory($newCategory);
            }

            // and insert it
            $this->entityManager->persist($achievement);
            $this->entityManager->flush();

        }

        

    }

}