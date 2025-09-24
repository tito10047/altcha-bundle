<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Tests\Unit;

use Tito10047\AltchaBundle\DependencyInjection\Compiler\AltchaBundleCompilerPass;
use Tito10047\AltchaBundle\AltchaBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AltchaBundleTest extends TestCase
{
    public function testBuild(): void
    {
        $container = new ContainerBuilder();
        $bundle = new AltchaBundle();
        $bundle->build($container);

        $this->assertNotEmpty(array_filter(
            $container->getCompilerPassConfig()->getPasses(),
            function ($value) {
                return $value instanceof AltchaBundleCompilerPass;
            }
        ));
    }
}
