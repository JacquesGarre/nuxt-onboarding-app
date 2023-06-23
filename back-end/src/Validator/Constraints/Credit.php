<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class Credit extends Constraint
{
    public $message = 'You don\'t have enough credit.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}