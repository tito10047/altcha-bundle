<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class HulutiAltchaBundleCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasParameter('twig.form.resources')) {
            /** @var array<int, string> $resources */
            $resources = $container->getParameter('twig.form.resources') ?: [];
            array_unshift($resources, '@HulutiAltcha/templates/fields.html.twig');
            $container->setParameter('twig.form.resources', $resources);
        }
    }
}
