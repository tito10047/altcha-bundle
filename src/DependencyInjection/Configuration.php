<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UndefinedInterfaceMethod
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('huluti_altcha');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->booleanNode('enable')->defaultTrue()->end()
            ->booleanNode('floating')->defaultFalse()->end()
            ->booleanNode('use_stimulus')->defaultNull()->end()
            ->booleanNode('hide_logo')->defaultTrue()->end()
            ->booleanNode('hide_footer')->defaultTrue()->end()
            ->scalarNode('altcha_js_path')->defaultValue('https://eu.altcha.org/js/latest/altcha.min.js')->end()
            ->scalarNode('hmacKey')->isRequired()->cannotBeEmpty()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
