<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Service;

use AltchaOrg\Altcha\Solution;

interface SolveChallengeResolverInterface
{
    public function solveChallenge(): Solution;
}
