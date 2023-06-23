<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class UserRolesValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {   
        if(!empty($value)){
            foreach($value as $role){
                if (!in_array($role, ["ROLE_ADMIN", "ROLE_USER"])) {
                    $this->context->buildViolation($constraint->message)->addViolation();
                }
            }
        }
    }
}