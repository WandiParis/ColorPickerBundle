<?php

namespace Wandi\ColorPickerBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class HexColor extends Constraint
{
    public $message = 'The color {{ value }} is not a valid hexadecimal value.';
}