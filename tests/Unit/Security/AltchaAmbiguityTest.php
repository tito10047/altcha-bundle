<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Tests\Unit\Security;

use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\CheckChallengeOptions;
use AltchaOrg\Altcha\Hasher\Algorithm;
use PHPUnit\Framework\TestCase;

/**
 * Regression test for ALTCHA PoW HMAC input ambiguity (parameter/nonce splicing).
 *
 * The vulnerable implementation computes the challenge as hash(salt . number) without
 * an unambiguous delimiter. This allows constructing two different (salt, number)
 * pairs that result in an identical concatenated string prior to hashing.
 *
 * Secure (patched) behavior: verification MUST fail when the (salt, number) in the
 * payload do not semantically match the challenge/signature.
 *
 * Vulnerable behavior (current, pre-fix): verification incorrectly returns true.
 */
final class AltchaAmbiguityTest extends TestCase
{
    public function testChallengeVerificationFailsForSplicedPayloadOnPatchedVersion(): void
    {
        $altcha = new Altcha('test-hmac-key');

        // Two different (salt, number) pairs that produce the same concatenation: "abc123"
        $optionsA = new CheckChallengeOptions(Algorithm::SHA256, 'abc', 123);
        $optionsB = new CheckChallengeOptions(Algorithm::SHA256, 'abc1', 23);

        // Note: On patched versions, the library appends an unambiguous delimiter to the salt,
        // so concatenations like "salt . number" will differ for (A) vs (B). We therefore
        // do not assert any concatenation equality here. The core of this test is that a
        // spliced payload must be rejected by a patched implementation.

        // Create a valid challenge/signature for pair A
        $challengeA = $altcha->createChallenge($optionsA);

        // Build payload B but splice in challenge and signature from A (replay/splicing attempt)
        $payloadB = [
            'algorithm' => Algorithm::SHA256->value,
            'challenge' => $challengeA->challenge,
            'number' => $optionsB->number,
            'salt' => $optionsB->salt,
            'signature' => $challengeA->signature,
        ];

        // On a secure (patched) version this MUST be false. On vulnerable version it returns true.
        $isValid = $altcha->verifySolution($payloadB, false);

        // Expectation for patched behavior (causes failure on vulnerable code):
        $this->assertFalse($isValid, 'Spliced payload should be rejected by patched implementation.');
    }
}
