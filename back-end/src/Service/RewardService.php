<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Achievement;
use App\Entity\User;
use App\Entity\UserNode;
use App\Entity\Quest;
use App\Entity\UserAchievement;
use App\Entity\UserQuest;
use App\Entity\UserContinent;
use App\Entity\UserCountry;
use App\Entity\UserRegion;
use App\Entity\UserStory;
use App\Entity\UserEpic;
use App\Entity\Comment;
use App\Entity\Continent;
use App\Entity\Country;
use App\Entity\Like;
use App\Entity\Story;
use App\Entity\Epic;
use App\Entity\Invitation;
use App\Repository\ArticleRepository;
use App\Repository\AchievementRepository;
use App\Repository\MobileAppSettingsRepository;
use App\Repository\RankRepository;
use App\Repository\InvitationRepository;
use App\Repository\ContinentRepository;
use App\Repository\CountryRepository;
use App\Repository\RegionRepository;
use Doctrine\Persistence\ObjectManager;
use DateTime;
use DateTimeImmutable;
use geoPHP;
use JsonMachine\Items;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\CheckForUnlockedItemsOnNewUserNode;
use App\Message\EarnExperience;
use App\Message\TriggerAwards;
use App\Message\FindAchievementsAroundUserNode;
use App\Service\MobileNotificationService;

class RewardService {

    private $articleRepository;
    private $achievementRepository;
    private $mobileAppSettingsRepository;
    private $rankRepository;
    private $invitationRepository;
    private $continentRepository;
    private $countryRepository;
    private $regionRepository;
    private $mobileNotificationService;

    public function __construct(
        ArticleRepository $articleRepository,
        AchievementRepository $achievementRepository,
        MobileAppSettingsRepository $mobileAppSettingsRepository,
        RankRepository $rankRepository,
        InvitationRepository $invitationRepository,
        ContinentRepository $continentRepository,
        CountryRepository $countryRepository,
        RegionRepository $regionRepository,
        MessageBusInterface $messageBus,
        MobileNotificationService $mobileNotificationService
    )
    {
        $this->articleRepository = $articleRepository;
        $this->achievementRepository = $achievementRepository;
        $this->mobileAppSettingsRepository = $mobileAppSettingsRepository;
        $this->rankRepository = $rankRepository;
        $this->invitationRepository = $invitationRepository;
        $this->continentRepository = $continentRepository;
        $this->countryRepository = $countryRepository;
        $this->regionRepository = $regionRepository;
        $this->messageBus = $messageBus;
        $this->mobileNotificationService = $mobileNotificationService;
    }

    public function canGiveReward(mixed $entity)
    {
        return $entity instanceof Achievement 
            || $entity instanceof Article 
            || $entity instanceof Quest 
            || $entity instanceof Story
            || $entity instanceof Epic
            || $entity instanceof UserNode
            || $entity instanceof Invitation
            || $entity instanceof Comment
            || $entity instanceof Like; 
    }

    public function postPersistRewards(?User $user, mixed $entity, ObjectManager $entityManager)
    {   
        if(!$this->canGiveReward($entity)){
            return;
        }

        // Check if new continent, country, region or achievement has been unlocked
        if ($entity instanceof UserNode) {

            if(!empty($user)){
                $message = new CheckForUnlockedItemsOnNewUserNode($entity, $user);
                $this->messageBus->dispatch($message);
            }


            // Fetch existing achievement around user node
            $existingAchievements = $this->achievementRepository->findAchievementsByRadiusAroundPoint(
                $entity->getLatitude(), 
                $entity->getLongitude(), 
                $_ENV['RADIUS_TO_TRIGGER_SEARCH']
            );

            
            // If no achievements exists around 'RADIUS_TO_TRIGGER_SEARCH' distance : 
            if(empty($existingAchievements)){
                // Try to find achievements around 10kms
                $message = new FindAchievementsAroundUserNode($entity);
                $this->messageBus->dispatch($message);
            }

        }

        if(!empty($user)){

            // Earn by experience by action (article created, achievement created, new user node added, new comment, new like)
            $message = new EarnExperience($entity, $user);
            $this->messageBus->dispatch($message);

            // Trigger awards
            $message = new TriggerAwards($entity, $user);
            $this->messageBus->dispatch($message);
        }

    }

    public function postUpdateRewards(?User $user, mixed $entity, ObjectManager $entityManager)
    {   
        if(!$this->canGiveReward($entity)){
            return;
        }

        // Trigger awards
        $message = new TriggerAwards($entity, $user);
        $this->messageBus->dispatch($message);

        // Update user
        $entityManager->persist($user);
        $entityManager->flush();

    }

    public function updateRank(User $user): User
    {
        $currentRankExperience = !empty($user->getRank()) ? $user->getRank()->getExperience() : 0;
        $newRank = $this->rankRepository->findByExperienceBetween($currentRankExperience, $user->getExperience());
        if(!empty($newRank) && $newRank !== $user->getRank()){
            $user->setRank($newRank);
            $this->mobileNotificationService->sendNotification(
                'New rank unlocked!',
                'You have been promoted to '.$newRank->getName(),
                $user
            );
        }
        return $user;
    }

    public function earnAchievementsByExperience(User $user): User
    {
        $newExperienceAchievements = $this->achievementRepository->findNewAchievementsTriggeredByExperience($user);
        foreach($newExperienceAchievements as $newAchievement){
            $achievement = new UserAchievement();
            $achievement->setUser($user);
            $achievement->setAchievement($newAchievement);
            $achievement->setCreatedAt(new DateTimeImmutable());
            if($user->isNewUserAchievement($achievement)){
                $user->addUserAchievement($achievement);
                $this->mobileNotificationService->sendNotification(
                    'New achievement unlocked!',
                    $newAchievement->getName() .' : '.$newAchievement->getDescription(),
                    $user
                );
            }
        }
        return $user;
    }

    public function earnAchievementsByPerimeter(User $user, UserNode $userNode): User
    {
        $newPerimeterAchievements = $this->achievementRepository->findNewAchievementsTriggeredByPerimeter($userNode);
        foreach($newPerimeterAchievements as $newAchievement){
            $achievement = new UserAchievement();
            $achievement->setUser($user);
            $achievement->setAchievement($newAchievement);
            $achievement->setCreatedAt(new DateTimeImmutable());
            if($user->isNewUserAchievement($achievement)){
                $user->addUserAchievement($achievement);
                $this->mobileNotificationService->sendNotification(
                    'New achievement unlocked!',
                    $newAchievement->getName() .' : '.$newAchievement->getDescription(),
                    $user
                );
            }
        }
        return $user;
    }

    public function earnAchievementsByCountOfPublishedAchievements(User $user): User
    {
        $count = count($this->achievementRepository->findBy([
            'author' => $user,
            'status' => 'active'
        ]));
        $newActionsAchievements = $this->achievementRepository->findNewAchievementsTriggeredByAction('Achievement', $count);
        foreach($newActionsAchievements as $newAchievement){
            $achievement = new UserAchievement();
            $achievement->setUser($user);
            $achievement->setAchievement($newAchievement);
            $achievement->setCreatedAt(new DateTimeImmutable());
            if($user->isNewUserAchievement($achievement)){
                $user->addUserAchievement($achievement);
                $this->mobileNotificationService->sendNotification(
                    'New achievement unlocked!',
                    $newAchievement->getName() .' : '.$newAchievement->getDescription(),
                    $user
                );
            }
        }
        return $user;
    }

    public function earnAchievementsByCountOfPublishedArticles(User $user): User
    {
        $count = count($this->articleRepository->findBy([
            'author' => $user,
            'status' => 'published'
        ]));
        $newActionsAchievements = $this->achievementRepository->findNewAchievementsTriggeredByAction('Article', $count);
        foreach($newActionsAchievements as $newAchievement){
            $achievement = new UserAchievement();
            $achievement->setUser($user);
            $achievement->setAchievement($newAchievement);
            $achievement->setCreatedAt(new DateTimeImmutable());
            if($user->isNewUserAchievement($achievement)){  
                $user->addUserAchievement($achievement);
                $this->mobileNotificationService->sendNotification(
                    'New achievement unlocked!',
                    $newAchievement->getName() .' : '.$newAchievement->getDescription(),
                    $user
                );
            }

        }
        return $user;
    }

    public function earnAchievementsByCountOfAcceptedInvitations(User $user): User
    {
        $count = count($this->invitationRepository->findBy([
            'user' => $user,
            'status' => 'accepted'
        ]));
        $newActionsAchievements = $this->achievementRepository->findNewAchievementsTriggeredByAction('Invitation', $count);
        foreach($newActionsAchievements as $newAchievement){
            $achievement = new UserAchievement();
            $achievement->setUser($user);
            $achievement->setAchievement($newAchievement);
            $achievement->setCreatedAt(new DateTimeImmutable());
            if($user->isNewUserAchievement($achievement)){ 
                $user->addUserAchievement($achievement);
                $this->mobileNotificationService->sendNotification(
                    'New achievement unlocked!',
                    $newAchievement->getName() .' : '.$newAchievement->getDescription(),
                    $user
                );
            }
        }
        return $user;
    }

    public function earnExperienceByAction(User $user, mixed $entity): User
    {
        if($entity instanceof Article){
            $settingLabel = 'EXPERIENCE_GAIN_WHEN_NEW_ARTICLE';
        } else if($entity instanceof Achievement){
            $settingLabel = 'EXPERIENCE_GAIN_WHEN_NEW_ACHIEVEMENT';
        } else if($entity instanceof UserNode){
            $settingLabel = 'EXPERIENCE_GAIN_WHEN_NEW_NODE';
        } else if($entity instanceof Like){
            $settingLabel = 'EXPERIENCE_GAIN_WHEN_NEW_LIKE';
        } else if($entity instanceof Comment){
            $settingLabel = 'EXPERIENCE_GAIN_WHEN_NEW_COMMENT';
        } else if($entity instanceof Invitation){
            $settingLabel = 'EXPERIENCE_GAIN_WHEN_NEW_INVITATION';
        } else {
            return $user;
        }

        $setting = $this->mobileAppSettingsRepository->findOneBy(['label' => $settingLabel]);
        $experienceGain = !empty($setting) ? $setting->getValue() : $_ENV[$settingLabel];
        if(!is_numeric($experienceGain)){
            $experienceGain = 0;
        }
        $experience = !empty($user->getExperience()) ? (int) $user->getExperience() : 0;
        $experience += $experienceGain;
        $user->setExperience($experience);
        return $user;
    }

    public function unlockQuestsByAchievements(User $user): User
    {
        foreach($user->getUserAchievements() as $userAchievement){
            $quests = $userAchievement->getAchievement()->getQuests();
            $now = new DateTime();
            foreach($quests as $quest){
                if($quest->isTemporary() && ($now >= $quest->getEndsAt() || $now < $quest->getStartsAt())){
                    continue;
                }
                $userQuest = new UserQuest();
                $userQuest->setUser($user);
                $userQuest->setQuest($quest);
                $userQuest->setStartedAt(new DateTimeImmutable());
                $userQuest->setStatus('in-progress');
                $userQuest->setExperienceEarned(0);
                if($user->isNewUserQuest($userQuest)){ 
                    $user->addUserQuest($userQuest);
                    $this->mobileNotificationService->sendNotification(
                        'New quest unlocked!',
                        $quest->getTitle(),
                        $user
                    );
                }
            }   
        }
        return $user;
    }

    public function finishQuests(User $user, ObjectManager $entityManager): User
    {
        
        // check if any user quest is finished, if so, update it and add xp to user
        $userAchievementsIDS = array_map(function($userAchievement){
            return $userAchievement->getAchievement()->getId();
        }, iterator_to_array($user->getUserAchievements()));

        foreach($user->getUserQuests() as $userQuest){

            $now = new DateTime();

            // Ignore already finished quests
            if($userQuest->getStatus() == 'finished'){
                continue;
            }

            // Has the quest expired?
            $now = new DateTime();
            if($userQuest->getQuest()->isTemporary() && $now >= $userQuest->getQuest()->getEndsAt()){
                $userQuest->setStatus('finished');
                $userQuest->setExperienceEarned(0);
                $entityManager->persist($userQuest);
                $entityManager->flush();
                continue;
            }

            if($userQuest->isCompleted()){
                $experienceEarned = $userQuest->getQuest()->getExperienceAward();

                // If quest is finished, then update it
                $userQuest->setStatus('finished');
                $userQuest->setEndedAt(new DateTimeImmutable());
                $userQuest->setExperienceEarned($experienceEarned);
                $entityManager->persist($userQuest);
                $entityManager->flush();

                $this->mobileNotificationService->sendNotification(
                    'You finished a quest!',
                    'You just earned +'.$experienceEarned.'XP for finishing : '.$userQuest->getQuest()->getTitle(),
                    $user
                );
                
                // Add xp to user
                $newExperience = $user->getExperience() + $experienceEarned;
                $user->setExperience($newExperience);
            }

        }
        return $user;
    }

    public function unlockRegion(?User $user, mixed $entity, ObjectManager $entityManager, Country $country): User
    {

        $point = geoPHP::load("POINT(".$entity->getLongitude()." ".$entity->getLatitude().")","wkt"); // longitude latitude
        
        $regionOfPoint = false;

        // regions of this country
        $regions = $this->regionRepository->findBy(['country' => $country]);

        foreach($regions as $region){
            $json = $region->getGeojson();
            if(empty($json)){
                continue;
            }

            $decodedJson = json_decode($json, true);
            $multipolygon = geoPHP::load($json, 'json');
            $polygons = $decodedJson['type'] == 'MultiPolygon' ? $multipolygon->components : [$multipolygon];
            foreach($polygons as $polygon){
                if($polygon->pointInPolygon($point)){
                    $regionOfPoint = $region;
                    break 2;
                }
            }
        }   

        if(!empty($regionOfPoint)){
            $userRegion = new UserRegion();
            $userRegion->setUser($user);
            $userRegion->setRegion($regionOfPoint);
            $userRegion->setCreatedAt(new DateTimeImmutable());
            if($user->isNewUserRegion($userRegion)){
                $user->addUserRegion($userRegion);
                $this->mobileNotificationService->sendNotification(
                    'New region discovered!',
                    'You just discovered '.$regionOfPoint->getName().' ('.$regionOfPoint->getCountry()->getName().')',
                    $user
                );
            }

        }

        return $user;

    }

    public function unlockCountry(?User $user, mixed $entity, ObjectManager $entityManager, Continent $continent): array
    {

        $point = geoPHP::load("POINT(".$entity->getLongitude()." ".$entity->getLatitude().")","wkt"); // longitude latitude
        
        $countryOfPoint = false;

        // Check in countries of this continent
        $countries = $this->countryRepository->findBy(['continent' => $continent]);

        foreach($countries as $country){
            $json = $country->getGeojson();
            if(empty($json)){
                continue;
            }

            $decodedJson = json_decode($json, true);
            $multipolygon = geoPHP::load($json, 'json');
            $polygons = [$multipolygon];
            if($decodedJson['type'] == 'MultiPolygon'){
                $polygons = $multipolygon->components;
            }

            foreach($polygons as $polygon){
                if($polygon->pointInPolygon($point)){
                    $countryOfPoint = $country;
                    break 2;
                }
            }
        }   

        if(!empty($countryOfPoint)){
            $userCountry = new UserCountry();
            $userCountry->setUser($user);
            $userCountry->setCountry($countryOfPoint);
            $userCountry->setCreatedAt(new DateTimeImmutable());
            if($user->isNewUserCountry($userCountry)){
                $user->addUserCountry($userCountry);
                $this->mobileNotificationService->sendNotification(
                    'New country discovered!',
                    'You just discovered '.$countryOfPoint->getName().' ('.$countryOfPoint->getContinent()->getName().')',
                    $user
                );
            }
        }

        return [$user, $countryOfPoint];

    }


    public function unlockContinent(?User $user, mixed $entity, ObjectManager $entityManager): array
    {
        $continent = $this->continentRepository->findByGivenPoint($entity->getLatitude(), $entity->getLongitude());
        if(!empty($continent)){
            $userContinent = new UserContinent();
            $userContinent->setUser($user);
            $userContinent->setContinent($continent);
            $userContinent->setCreatedAt(new DateTimeImmutable());

            if($user->isNewUserContinent($userContinent)){
                $user->addUserContinent($userContinent);
                $this->mobileNotificationService->sendNotification(
                    'New continent discovered!',
                    'You just discovered '.$continent->getName(),
                    $user
                );
            }
        }
        return [$user, $continent];
    }

    public function unlockStoriesByQuests(User $user): User
    {

        foreach($user->getUserQuests() as $userQuest){
            $stories = $userQuest->getQuest()->getStories();
            foreach($stories as $story){
                $userStoryIDS = array_map(function($userStory){
                    return $userStory->getStory()->getId();
                }, iterator_to_array($user->getUserStories()));
                if(!in_array($story->getId(), $userStoryIDS)){
                    $userStory = new UserStory();
                    $userStory->setUser($user);
                    $userStory->setStory($story);
                    $userStory->setStartedAt(new DateTimeImmutable());
                    $userStory->setStatus('in-progress');
                    $userStory->setExperienceEarned(0);
                    if($user->isNewUserStory($userStory)){
                        $user->addUserStory($userStory);
                        $this->mobileNotificationService->sendNotification(
                            'You unlocked a new story!',
                            'You just unlocked : '.$story->getName(),
                            $user
                        );
                    }

                }
            }   
        }
        return $user;
    }

    public function unlockEpicsByStories(User $user): User
    {

        foreach($user->getUserStories() as $userStory){
            $epics = $userStory->getStory()->getEpics();
            foreach($epics as $epic){
                $userEpicIDS = array_map(function($userEpic){
                    return $userEpic->getEpic()->getId();
                }, iterator_to_array($user->getUserEpics()));
                if(!in_array($epic->getId(), $userEpicIDS)){
                    $userEpic = new UserEpic();
                    $userEpic->setUser($user);
                    $userEpic->setEpic($epic);
                    $userEpic->setStartedAt(new DateTimeImmutable());
                    $userEpic->setStatus('in-progress');
                    $userEpic->setExperienceEarned(0);
                    if($user->isNewUserEpic($userEpic)){
                        $user->addUserEpic($userEpic);
                        $this->mobileNotificationService->sendNotification(
                            'You unlocked a new epic!',
                            'You just unlocked : '.$epic->getName(),
                            $user
                        );
                    }
                }
            }   
        }
        return $user;
    }


    public function finishStories(User $user, ObjectManager $entityManager): User
    {
        
        // check if any user quest is finished, if so, update it and add xp to user
        $userQuestsIDS = array_map(function($userQuest){
            return $userQuest->getQuest()->getId();
        }, iterator_to_array($user->getUserQuests()));

        foreach($user->getUserStories() as $userStory){

            if($userStory->getStatus() == 'finished'){
                continue;
            }

            // Test if quest is completed
            if($userStory->isCompleted()){
                $experienceEarned = $userStory->getStory()->getExperienceAward();

                // If quest is finished, then update it
                $userStory->setStatus('finished');
                $userStory->setEndedAt(new DateTimeImmutable());
                $userStory->setExperienceEarned($experienceEarned);
                $entityManager->persist($userStory);
                $entityManager->flush();

                $this->mobileNotificationService->sendNotification(
                    'You finished a story!',
                    'You just earned +'.$experienceEarned.'XP for finishing : '.$story->getName(),
                    $user
                );
                
                // Add xp to user
                $newExperience = $user->getExperience() + $experienceEarned;
                $user->setExperience($newExperience);
            }

        }
        return $user;
    }

    public function finishEpics(User $user, ObjectManager $entityManager): User
    {
        
        // check if any user epic is finished, if so, update it and add xp to user
        $userStoriesIDS = array_map(function($userStory){
            return $userStory->getStory()->getId();
        }, iterator_to_array($user->getUserStories()));

        foreach($user->getUserEpics() as $userEpic){

            if($userEpic->getStatus() == 'finished'){
                continue;
            }

            // Test if epic is completed
            if($userEpic->isCompleted()){
                $experienceEarned = $userEpic->getEpic()->getExperienceAward();

                // If quest is finished, then update it
                $userEpic->setStatus('finished');
                $userEpic->setEndedAt(new DateTimeImmutable());
                $userEpic->setExperienceEarned($experienceEarned);
                $entityManager->persist($userEpic);
                $entityManager->flush();

                $this->mobileNotificationService->sendNotification(
                    'You finished an epic!',
                    'You just earned +'.$experienceEarned.'XP for finishing : '.$epic->getName(),
                    $user
                );
                
                // Add xp to user
                $newExperience = $user->getExperience() + $experienceEarned;
                $user->setExperience($newExperience);
            }


        }
        return $user;
    }

}