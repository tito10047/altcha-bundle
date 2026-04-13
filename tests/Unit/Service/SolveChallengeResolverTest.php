<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Tests\Unit\Service;

use AltchaOrg\Altcha\Solution;
use PHPUnit\Framework\TestCase;
use Tito10047\AltchaBundle\Service\ChallengeResolver;
use Tito10047\AltchaBundle\Service\DriverKeyProvider;
use Tito10047\AltchaBundle\Service\SolveChallengeResolver;

class SolveChallengeResolverTest extends TestCase
{
    public function testSolveChallenge(): void
    {
        $hmacSignature = 'test_hmac_signature';
        $hmacKeySignature = 'test_hmac_key_signature';
        $timeout = 30.0;
        $counterMin = 1;
        $counterMax = 100;
        $expiresAt = '+1 hour';

        $resolver = new ChallengeResolver(
            new DriverKeyProvider('SHA-256'),
            $hmacSignature,
            $hmacKeySignature,
            100, // Menšia náročnosť pre rýchlejšie riešenie
            $counterMin,
            $counterMax,
            $expiresAt
        );

        $solveResolver = new SolveChallengeResolver(
            $resolver,
            $hmacSignature,
            $hmacKeySignature,
            $timeout
        );

        $solution = $solveResolver->solveChallenge();

        $this->assertInstanceOf(Solution::class, $solution);
        $this->assertNotNull($solution->counter);
        $this->assertNotNull($solution->derivedKey);
    }
}
