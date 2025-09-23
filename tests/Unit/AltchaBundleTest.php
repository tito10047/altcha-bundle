<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\Tests\Unit;

use Huluti\AltchaBundle\DependencyInjection\Compiler\HulutiAltchaBundleCompilerPass;
use Huluti\AltchaBundle\HulutiAltchaBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AltchaBundleTest extends TestCase
{
    public function testBuild(): void
    {
        $container = new ContainerBuilder();
        $bundle = new HulutiAltchaBundle();
        $bundle->build($container);

        $this->assertNotEmpty(array_filter(
            $container->getCompilerPassConfig()->getPasses(),
            function ($value) {
                return $value instanceof HulutiAltchaBundleCompilerPass;
            }
        ));
    }
}
