<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UserRoles extends Constraint
{
    public $message = 'User roles can only be ROLE_USER and/or ROLE_ADMIN';
}