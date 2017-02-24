<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Port.
 *
 * @Annotation
 */
class Port extends Constraint
{
    /**
     * Message which will be displayed when decided to give the user a feedback.
     *
     * @var string
     */
    public $message = "%string% is not a valid port.";
}
