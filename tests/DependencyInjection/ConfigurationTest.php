<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\Tests\DependencyInjection;

use Huluti\AltchaBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    public function testProcessConfiguration(): void
    {
        $configuration = new Configuration();
        $processor = new Processor();
        $config = $processor->processConfiguration($configuration, [
            'huluti_altcha' => [
                'hmacKey' => 'test',
            ],
        ]);

        $this->assertArrayHasKey('enable', $config);
        $this->assertTrue($config['enable']);

        $this->assertArrayHasKey('floating', $config);
        $this->assertFalse($config['floating']);

        $this->assertArrayHasKey('use_stimulus', $config);
        $this->assertNull($config['use_stimulus']);

        $this->assertArrayHasKey('use_asset_mapper', $config);
        $this->assertNull($config['use_asset_mapper']);

        $this->assertArrayHasKey('hide_logo', $config);
        $this->assertFalse($config['hide_logo']);

        $this->assertArrayHasKey('hide_footer', $config);
        $this->assertFalse($config['hide_footer']);

        $this->assertArrayHasKey('altcha_js_path', $config);
        $this->assertIsString($config['altcha_js_path']);

        $this->assertArrayHasKey('hmacKey', $config);
        $this->assertNotNull($config['hmacKey']);
    }
}
