<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Tests\Unit\DependencyInjection;

use Tito10047\AltchaBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    public function testProcessConfiguration(): void
    {
        $configuration = new Configuration();
        $processor = new Processor();
        $config = $processor->processConfiguration($configuration, [
            'altcha' => [
                'hmacSignature' => 'test_sig',
                'hmacKeySignature' => 'test_key_sig',
            ],
        ]);

        $this->assertArrayHasKey('hmacSignature', $config);
        $this->assertEquals('test_sig', $config['hmacSignature']);

        $this->assertArrayHasKey('hmacKeySignature', $config);
        $this->assertEquals('test_key_sig', $config['hmacKeySignature']);

        $this->assertEquals(5000, $config['cost']);
        $this->assertEquals(5000, $config['counter_min']);
        $this->assertEquals(10000, $config['counter_max']);
        $this->assertEquals(30, $config['timeout']);
        $this->assertEquals(100000, $config['max_number']);

        // Test migration from hmacKey
        $config = $processor->processConfiguration($configuration, [
            'altcha' => [
                'hmacKey' => 'legacy_key',
            ],
        ]);

        $this->assertArrayHasKey('hmacSignature', $config);
        $this->assertEquals('legacy_key', $config['hmacSignature']);
        $this->assertArrayHasKey('hmacKey', $config);
        $this->assertEquals('legacy_key', $config['hmacKey']);
    }

    public function testMissingHmacThrowsException(): void
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\InvalidConfigurationException::class);
        $this->expectExceptionMessage('The child config "hmacSignature" under "altcha" must be configured.');

        $configuration = new Configuration();
        $processor = new Processor();
        $processor->processConfiguration($configuration, [
            'altcha' => [],
        ]);
    }
}
