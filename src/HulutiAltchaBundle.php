<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle;

use Huluti\AltchaBundle\HulutiAltchaBundleCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HulutiAltchaBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new HulutiAltchaBundleCompilerPass());
    }
}
