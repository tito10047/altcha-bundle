<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\Validator;

use Symfony\Component\Validator\Constraint;

final class Altcha extends Constraint
{
    /**
     * @var string
     */
    public $message = 'invalid_altcha';
}
