<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle;

use Tito10047\AltchaBundle\DependencyInjection\Compiler\AltchaBundleCompilerPass;
use Tito10047\AltchaBundle\DependencyInjection\AltchaExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class AltchaBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new AltchaBundleCompilerPass());
    }

    #[\Override]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new AltchaExtension();
    }
}
