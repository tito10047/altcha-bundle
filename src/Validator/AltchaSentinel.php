<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\Validator;

use Symfony\Component\Validator\Constraint;

final class AltchaSentinel extends Constraint
{
    /**
     * @var string
     */
    public $message = 'invalid_altcha';
}
