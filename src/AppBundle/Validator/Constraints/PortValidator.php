<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class PortValidator.
 */
class PortValidator extends ConstraintValidator
{
    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (is_null($value)) return; // Allows not passing the value.
        /** @var Port $constraint */
        $castedInt = (int) $value;
        if ($castedInt == $value) { // If the weak comparison matches, it is valid.
            if ($castedInt < 65535 && $castedInt > 0) { // Port is in the port range.
                return;
            }
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('%string%', $value)
            ->addViolation();
    }
}
