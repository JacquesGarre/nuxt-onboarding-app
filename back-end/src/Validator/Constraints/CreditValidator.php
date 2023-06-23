<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Entity\Article;
use App\Entity\Achievement;
use App\Entity\Quest;
use App\Entity\Story;
use App\Entity\Epic;
use App\Entity\Place;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Bundle\SecurityBundle\Security;

final class CreditValidator extends ConstraintValidator
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function validate($entity, Constraint $constraint): void
    {   
        $user = $this->security->getUser();

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

        if (!$constraint instanceof Credit) {
            throw new UnexpectedValueException($constraint, Credit::class);
        }

        // Test private
        if($entity->isPrivate() && $user->{'getNbPrivate'.$name.'Left'}() < 1){
            $this->context->buildViolation($constraint->message)->atPath('user.nbPrivate'.$name.'Left')->addViolation();
        }

        // Test sponsored
        if($entity->isSponsored() && $user->{'getNbSponsored'.$name.'Left'}() < 1){
            $this->context->buildViolation($constraint->message)->atPath('user.nbSponsored'.$name.'Left')->addViolation();
        }
        
    }

}