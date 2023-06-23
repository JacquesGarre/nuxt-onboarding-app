<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Achievement;
use App\Entity\Quest;
use App\Entity\Story;
use App\Entity\Epic;
use App\Entity\Place;


class CreditService {

    public function postPersistCredit($user, $entity, $entityManager)
    {
        
        if(!$this->hasCredit($entity)){
            return;
        }

        if ($entity instanceof Article) {
            $name = 'Articles';
        }
        if ($entity instanceof Achievement) {
            $name = 'Achievements';
        }
        if ($entity instanceof Quest) {
            $name = 'Quests';
        }
        if ($entity instanceof Story) {
            $name = 'Stories';
        }
        if ($entity instanceof Epic) {
            $name = 'Epics';
        }
        if ($entity instanceof Place) {
            $name = 'Places';
        }

        if($entity->isPrivate()){
            $nb = $user->{'getNbPrivate'.$name.'Left'}() - 1;
            $user->{'setNbPrivate'.$name.'Left'}($nb);
        }

        if($entity->isSponsored()){
            $nb = $user->{'getNbSponsored'.$name.'Left'}() - 1;
            $user->{'setNbSponsored'.$name.'Left'}($nb);
        }

        // Update user
        if($user != null){
            $entityManager->persist($user);
            $entityManager->flush();
        }

    }

    private function hasCredit(mixed $entity): bool
    {
        return $entity instanceof Achievement 
            || $entity instanceof Article 
            || $entity instanceof Quest 
            || $entity instanceof Story
            || $entity instanceof Epic
            || $entity instanceof Place; 
    }
}