<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserNode;
use App\Entity\Achievement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @extends ServiceEntityRepository<Achievement>
 *
 * @method Achievement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Achievement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Achievement[]    findAll()
 * @method Achievement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AchievementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Achievement::class);
    }

    public function save(Achievement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Achievement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findNewAchievementsTriggeredByExperience(User $user): mixed
    {
        $currentAchievements = $user->getUserAchievements();
        $achievementsIDS = array_map(function($achievement){ 
            return $achievement->getAchievement()->getId(); 
        }, iterator_to_array($currentAchievements));
        $notInCond = !empty($achievementsIDS) ? "a.id NOT IN (".implode(',', $achievementsIDS).")" : "";


        $qry = $this->createQueryBuilder('a')
            ->andWhere('a.status = :status')
            ->setParameter('status', 'active') // All active achievements
            ->andWhere('a.triggerOn = :trigger_on')
            ->setParameter('trigger_on', 'experience_amount') // triggered on experience amount
            ->andWhere('a.experience <= :currentExperience')
            ->setParameter('currentExperience', $user->getExperience()); // where experience <= user experience
        if(!empty($notInCond))
        {
            $qry->andWhere($notInCond);
        }
        return $qry->orderBy('a.experience', 'ASC')
            ->getQuery()
            ->getResult();

    }

    public function findAchievementsByRadiusAroundPoint($lat, $long, $radius): mixed
    { 
        $distanceCondition = $radius.' > (111111 * DEGREES(
                ACOS(
                    LEAST(
                        1.0, 
                        COS(RADIANS(a.latitude)) * COS(RADIANS(:lat)) * COS(RADIANS(a.longitude - :long)) + SIN(RADIANS(a.latitude)) * SIN(RADIANS(:lat))
                    )
                )             
            )
        )
        ';

        $qry = $this->createQueryBuilder('a')
            ->andWhere($distanceCondition) 
            ->setParameter(':long', $long)
            ->setParameter(':lat', $lat)
            ->getQuery();

        return $qry->getResult();

    }


    public function findNewAchievementsTriggeredByPerimeter(UserNode $node): mixed
    {
        $long = $node->getLongitude();
        $lat = $node->getLatitude();    
        $distanceCondition = '
        a.radius > (111111 * DEGREES(
                ACOS(
                    LEAST(
                        1.0, 
                        COS(RADIANS(a.latitude)) * COS(RADIANS(:lat)) * COS(RADIANS(a.longitude - :long)) + SIN(RADIANS(a.latitude)) * SIN(RADIANS(:lat))
                    )
                )             
            )
        )
        ';

        $qry = $this->createQueryBuilder('a')
            ->andWhere('a.status = :status')
            ->setParameter('status', 'active') // All active achievements
            ->andWhere('a.triggerOn = :trigger_on')
            ->setParameter('trigger_on', 'in_perimeter') // triggered on in_perimeter
            ->andWhere($distanceCondition) 
            ->setParameter(':long', $long)
            ->setParameter(':lat', $lat)
            ->getQuery();

        
        return $qry->getResult();

    }

    public function findNewAchievementsTriggeredByAction($entity, $count): mixed
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.status = :status')
            ->setParameter('status', 'active') // All active achievements
            ->andWhere('a.triggerOn = :trigger_on')
            ->setParameter('trigger_on', 'actions_amount') // triggered on action_amount
            ->andWhere('a.action = :entity')
            ->setParameter(':entity', $entity)
            ->andWhere('a.actionCount <= :count')
            ->setParameter(':count', $count)
            ->getQuery()
            ->getResult();

    }


}
