<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\Service;

use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\Challenge;
use AltchaOrg\Altcha\ChallengeOptions;

class ChallengeResolver implements ChallengeResolverInterface
{
    public function __construct(
        private readonly string $hmacKey,
        private readonly string $expires,
    ) {
    }

    public function getChallenge(): Challenge
    {
        $options = new ChallengeOptions(
            maxNumber: 100000,
            expires: (new \DateTime())->modify($this->expires),
        );

        return (new Altcha($this->hmacKey))->createChallenge($options);
    }
}
