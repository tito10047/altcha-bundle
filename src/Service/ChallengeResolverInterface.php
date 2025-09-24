<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Service;

use AltchaOrg\Altcha\Challenge;

interface ChallengeResolverInterface
{
    public function getChallenge(): Challenge;
}
