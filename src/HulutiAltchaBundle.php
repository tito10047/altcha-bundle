<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle;

use Huluti\AltchaBundle\DependencyInjection\Compiler\HulutiAltchaBundleCompilerPass;
use Huluti\AltchaBundle\DependencyInjection\HulutiAltchaExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class HulutiAltchaBundle extends AbstractBundle
{

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new HulutiAltchaBundleCompilerPass());
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new HulutiAltchaExtension();
    }
}
