<?php

namespace Tito10047\AltchaBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Tito10047\AltchaBundle\Tests\App\Kernel;

class RateLimiterTest extends WebTestCase
{
    protected static function createKernel(array $options = []): KernelInterface
    {
        $kernel = new Kernel('test', 'RateLimiter/config');
		$kernel->clearCache();
		return $kernel;
    }

    public function testRateLimiterBlocksRepeatedChallenge(): void
    {
        $client = static::createClient();

        // Fixed challenge signature — same key used across all 3 submissions
        $signature = 'deadbeef1234567890abcdef1234567890abcdef1234567890abcdef12345678';

        $makePayload = static function (int $counter) use ($signature): string {
            return base64_encode((string) json_encode([
                'challenge' => [
                    'parameters' => [],
                    'signature'  => $signature,
                ],
                'solution' => [
                    'counter'    => $counter,
                    'derivedKey' => 'fake-key-' . $counter,
                    'time'       => 0,
                ],
            ]));
        };

        // 1st submission: rate limit allows (1 token consumed), solution is invalid
        $client->request('POST', '/', ['index' => ['altcha' => $makePayload(1)]]);
        $content = (string) $client->getResponse()->getContent();
        $this->assertStringNotContainsString('Too many attempts', $content, '1st attempt must not hit rate limit');
        $this->assertStringContainsString('Captcha not resolved', $content, '1st attempt: invalid solution triggers captcha error');

        // 2nd submission: same challenge signature, rate limit is now exhausted
        $client->request('POST', '/', ['index' => ['altcha' => $makePayload(2)]]);
        $this->assertStringContainsString(
            'Too many attempts',
            (string) $client->getResponse()->getContent(),
            '2nd attempt must be rate limited'
        );

        // 3rd submission: still rate limited
        $client->request('POST', '/', ['index' => ['altcha' => $makePayload(3)]]);
        $this->assertStringContainsString(
            'Too many attempts',
            (string) $client->getResponse()->getContent(),
            '3rd attempt must still be rate limited'
        );
    }
}
