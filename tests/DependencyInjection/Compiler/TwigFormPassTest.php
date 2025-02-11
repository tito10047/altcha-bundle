<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\Tests\DependencyInjection\Compiler;

use Huluti\AltchaBundle\DependencyInjection\Compiler\HulutiAltchaBundleCompilerPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigFormPassTest extends TestCase
{
    public function testProcessHasNotTwigFormResources(): void
    {
        $container = $this->createMock(ContainerBuilder::class);
        $container->expects($this->once())->method('hasParameter')
            ->with('twig.form.resources')->willReturn(false);

        $container->expects($this->never())->method('setParameter');

        $compiler = new HulutiAltchaBundleCompilerPass();
        $compiler->process($container);
    }

    public function testProcessHasTwigFormResources(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('twig.form.resources', ['foo']);

        $compiler = new HulutiAltchaBundleCompilerPass();
        $compiler->process($container);

        $this->assertSame(
            ['@HulutiAltcha/fields.html.twig', 'foo'],
            $container->getParameter('twig.form.resources')
        );
    }
}
