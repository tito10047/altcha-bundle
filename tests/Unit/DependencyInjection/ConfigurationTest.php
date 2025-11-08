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
                'hmacKey' => 'test',
            ],
        ]);

        $this->assertArrayHasKey('enable', $config);
        $this->assertTrue($config['enable']);

        $this->assertArrayHasKey('floating', $config);
        $this->assertFalse($config['floating']);

        $this->assertArrayHasKey('overlay', $config);
        $this->assertFalse($config['overlay']['enabled']);
        $this->assertNull($config['overlay']['content']);

        $this->assertArrayHasKey('use_stimulus', $config);
        $this->assertNull($config['use_stimulus']);

        $this->assertArrayHasKey('hide_logo', $config);
        $this->assertFalse($config['hide_logo']);

        $this->assertArrayHasKey('hide_footer', $config);
        $this->assertFalse($config['hide_footer']);

        $this->assertArrayHasKey('altcha_js_path', $config);
        $this->assertIsString($config['altcha_js_path']);

        $this->assertArrayHasKey('altcha_js_i18n_path', $config);
        $this->assertIsString($config['altcha_js_i18n_path']);

        $this->assertArrayHasKey('max_number', $config);
        $this->assertIsInt($config['max_number']);

        $this->assertArrayHasKey('expires', $config);
        $this->assertIsString($config['expires']);
		$this->assertNotNull(strtotime($config['expires']));

        $this->assertArrayHasKey('hmacKey', $config);
        $this->assertNotNull($config['hmacKey']);
    }
}
