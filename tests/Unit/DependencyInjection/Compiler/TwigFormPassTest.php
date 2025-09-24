<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Tests\Unit\DependencyInjection\Compiler;

use Tito10047\AltchaBundle\DependencyInjection\Compiler\AltchaBundleCompilerPass;
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

        $compiler = new AltchaBundleCompilerPass();
        $compiler->process($container);
    }

    public function testProcessHasTwigFormResources(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('twig.form.resources', ['foo']);

        $compiler = new AltchaBundleCompilerPass();
        $compiler->process($container);

        $this->assertSame(
            ['@Altcha/fields.html.twig', 'foo'],
            $container->getParameter('twig.form.resources')
        );
    }
}
