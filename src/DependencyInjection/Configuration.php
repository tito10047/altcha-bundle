<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('huluti_altcha');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->scalarNode('enable')
            ->defaultTrue()
            ->end()
            ->scalarNode('hmacKey')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
