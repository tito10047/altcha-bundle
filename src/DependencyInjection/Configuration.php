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
            ->booleanNode('use_asset_mapper')->defaultNull()->end()
            ->booleanNode('hide_logo')->defaultFalse()->end()
            ->booleanNode('hide_footer')->defaultFalse()->end()
            ->scalarNode('altcha_js_path')->defaultValue('https://eu.altcha.org/js/latest/altcha.min.js')->end()
            ->scalarNode('altcha_js_i18n_path')->defaultNull()->end()
            ->scalarNode('hmacKey')->isRequired()->cannotBeEmpty()->end()
            ->arrayNode('sentinel')->info(<<<TXT
               Enable usage of sentinel, if enabled:
                   - the widget will use the /v1/challenge endpoint to retrieve a new challenge instead of your app;
                   - the challenge resolution will be validated againt /v1/verify/signature endpoint that ensure protection againt replay attacks;
                   - the hmacKey provided in configuration is not used anymore.
               More information available at https://altcha.org/docs/v2/server-integration/
            TXT)
                ->canBeEnabled()
                ->children()
                     ->scalarNode('base_url')->isRequired()->cannotBeEmpty()
                          ->info('Your sentinel instance url, eg: https://sentinel.example.com')
                          ->end()
                     ->scalarNode('api_key')->isRequired()->cannotBeEmpty()
                         ->info('Your sentinel client api key')
                         ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
