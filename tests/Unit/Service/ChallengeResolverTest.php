<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Tests\Unit\Service;

use AltchaOrg\Altcha\Challenge;
use PHPUnit\Framework\TestCase;
use Tito10047\AltchaBundle\Service\ChallengeResolver;
use Tito10047\AltchaBundle\Service\DriverKeyProvider;

class ChallengeResolverTest extends TestCase
{
    public function testGetChallenge(): void
    {
        $hmacSignature = 'test_hmac_signature';
        $hmacKeySignature = 'test_hmac_key_signature';
        $cost = 1000;
        $counterMin = 1;
        $counterMax = 100;
        $expiresAt = '+1 hour';

        $resolver = new ChallengeResolver(
            new DriverKeyProvider('SHA-256'),
            $hmacSignature,
            $hmacKeySignature,
            $cost,
            $counterMin,
            $counterMax,
            $expiresAt
        );

        $challenge = $resolver->getChallenge();

        $this->assertInstanceOf(Challenge::class, $challenge);
        $this->assertEquals($cost, $challenge->parameters->cost);
        $this->assertNotNull($challenge->parameters->nonce);
        $this->assertNotNull($challenge->parameters->salt);
        $this->assertNotNull($challenge->signature);
    }
}
