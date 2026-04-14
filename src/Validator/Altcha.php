<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Validator;

use Symfony\Component\Validator\Constraint;

final class Altcha extends Constraint
{
    /**
     * @var string
     */
    public $message = 'invalid_altcha';

    /**
     * @var string
     */
    public $rateLimitMessage = 'altcha_rate_limit_exceeded';
}
