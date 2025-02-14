<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\Service;

use AltchaOrg\Altcha\Challenge;

interface ChallengeResolverInterface
{
    public function getChallenge(): Challenge;
}