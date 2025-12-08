<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UndefinedInterfaceMethod
	 * @psalm-suppress InvalidReturnType
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('altcha');
        $rootNode = $treeBuilder->getRootNode();

		// @phpstan-ignore-next-line
        $rootNode
            ->children()
            ->booleanNode('enable')->defaultTrue()->end()
            ->booleanNode('floating')->defaultFalse()->end()
            ->arrayNode('overlay')
                    ->beforeNormalization()
                        ->ifTrue(static function ($v) {
                            return \is_bool($v);
                        })
                        ->then(static function ($v) {
                            return ['enabled' => $v];
                        })
                    ->end()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->defaultFalse()
                        ->end()
                        ->scalarNode('content')
                            ->defaultValue(null)
                            ->info('CSS selector for overlay content')
                        ->end()
                    ->end()
                ->end() // end arrayNode('overlay')
            ->booleanNode('use_stimulus')->defaultNull()->end()
			->integerNode('max_number')->defaultValue(100000)->end()
			->scalarNode('expires')
				->defaultValue('+15 minute')
                ->cannotBeEmpty()
                ->validate()
                    ->ifTrue(static function ($v) {
                        return !\is_string($v) || false === \strtotime($v);
                    })
                    ->thenInvalid('Invalid time expression for "expires". Use a string parseable by strtotime, e.g., "+15 minutes" or "2025-12-31 23:59:00".')
                ->end()
            ->end()
            ->booleanNode('include_script')->defaultNull()->end()
            ->booleanNode('hide_logo')->defaultFalse()->end()
            ->booleanNode('hide_footer')->defaultFalse()->end()
            ->scalarNode('altcha_js_path')->defaultValue('https://eu.altcha.org/js/latest/altcha.min.js')->end()
            ->scalarNode('altcha_js_i18n_path')->defaultValue('https://cdn.jsdelivr.net/gh/altcha-org/altcha/dist_i18n/all.min.js')->end()
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
