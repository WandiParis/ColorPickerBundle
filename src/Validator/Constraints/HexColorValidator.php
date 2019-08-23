<?php

namespace Wandi\ColorPickerBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HexColorValidator extends ConstraintValidator
{
    /**
     * @param mixed $color
     * @param Constraint $constraint
     */
    public function validate($color, Constraint $constraint)
    {
        if (!empty(trim($color)) && 1 !== preg_match("/^#([0-9a-fA-F]{8}|[0-9a-fA-F]{6}|[0-9a-fA-F]{4}|[0-9a-fA-F]{3})$/", $color)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $color)
                ->addViolation();
        }
    }
}