<?php

namespace Tito10047\AltchaBundle\Tests\Unit\Service;

use AltchaOrg\Altcha\Algorithm\Pbkdf2;
use PHPUnit\Framework\TestCase;
use Tito10047\AltchaBundle\Service\DriverKeyProvider;

class DriverKeyProviderTest extends TestCase
{
    public function testGetAlgorithmReturnsPbkdf2WithCorrectAlgorithm(): void
    {
        $provider = new DriverKeyProvider('SHA-256');
        $algorithm = $provider->getAlgorithm();

        $this->assertInstanceOf(Pbkdf2::class, $algorithm);
    }

    public function testGetAlgorithmWithSha384(): void
    {
        $provider = new DriverKeyProvider('SHA-384');
        $algorithm = $provider->getAlgorithm();

        $this->assertInstanceOf(Pbkdf2::class, $algorithm);
    }

    public function testGetAlgorithmWithSha512(): void
    {
        $provider = new DriverKeyProvider('SHA-512');
        $algorithm = $provider->getAlgorithm();

        $this->assertInstanceOf(Pbkdf2::class, $algorithm);
    }

    public function testGetAlgorithmWithInvalidAlgorithmThrowsException(): void
    {
        $provider = new DriverKeyProvider('INVALID');

        $this->expectException(\ValueError::class);
        $provider->getAlgorithm();
    }
}
