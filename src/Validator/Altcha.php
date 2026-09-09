<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class Altcha extends Constraint
{
    /**
     * @var string
     */
    public $message = 'invalid_altcha';
}
