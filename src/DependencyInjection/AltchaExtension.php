<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\DependencyInjection;

use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\UX\StimulusBundle\StimulusBundle;
use Symfony\Component\DependencyInjection\Exception\LogicException;

class AltchaExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->registerAceEditorParameters($config, $container);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yml');
        if ($container->getParameter('altcha.use_sentinel')) {
            $loader->load('services_sentinel.yml');
        }
    }

    /**
     * Register parameters for the DI.
     *
     * @param array<mixed> $config
     */
    private function registerAceEditorParameters(array $config, ContainerBuilder $container): void
    {
		//
        if ($config['floating'] && $config['overlay']['enabled']) {
            throw new LogicException('You must choose betwen floating and overlay modes.');
        }

        $container->setParameter('altcha.enable', $config['enable']);
        $container->setParameter('altcha.floating', $config['floating']);
        $container->setParameter('altcha.overlay', $config['overlay']['enabled']);
        $container->setParameter('altcha.overlay_content', $config['overlay']['content']);
        $container->setParameter('altcha.hmacKey', $config['hmacKey']);
        $container->setParameter('altcha.hide_logo', $config['hide_logo']);
        $container->setParameter('altcha.hide_footer', $config['hide_footer']);
        $container->setParameter('altcha.js_path', $config['altcha_js_path']);
        $container->setParameter('altcha.i18n_path', $config['altcha_js_i18n_path']);
        $container->setParameter('altcha.max_number', $config['max_number']);
        $container->setParameter('altcha.expires', $config['expires']);

		if($config["include_script"]!==null){
			$container->setParameter('altcha.include_script', $config["include_script"]);
		}else {
			$assetMapperInstalled = interface_exists(AssetMapperInterface::class);
			$container->setParameter('altcha.include_script', !$assetMapperInstalled);
		}

        $useStimulus = $config['use_stimulus'];
        if (null === $useStimulus) {
            $bundles = $container->getParameter('kernel.bundles');
            assert(is_array($bundles));
            $useStimulus = in_array(StimulusBundle::class, $bundles, true);
        }

        $container->setParameter('altcha.use_stimulus', $useStimulus);
        /**
         * @var array{'enabled': bool, 'base_url': string, 'api_key': string} $sentinelConfig
         */
        $sentinelConfig = $config['sentinel'];
        if ($useSentinel = $sentinelConfig['enabled']) {
            $container->setParameter('altcha.sentinel.base_url', $sentinelConfig['base_url']);
            $container->setParameter('altcha.sentinel.api_key', $sentinelConfig['api_key']);
            /* @see https://altcha.org/docs/v2/widget-integration/ */
            $container->setParameter('altcha.sentinel.challenge_url', sprintf(
                '%s/v1/challenge?apiKey=%s',
                $sentinelConfig['base_url'],
                $sentinelConfig['api_key']
            ));
            /* @see https://altcha.org/docs/v2/server-integration/ */
            $container->setParameter('altcha.sentinel.verify_signature_url', sprintf(
                '%s/v1/verify/signature',
                $sentinelConfig['base_url']
            ));
        } else {
            $container->setParameter('altcha.sentinel.challenge_url', null);
        }
        $container->setParameter('altcha.use_sentinel', $useSentinel);
    }

    /**
     * @see https://symfony.com/doc/current/frontend/create_ux_bundle.html
     */
    public function prepend(ContainerBuilder $container): void
    {
        if ($this->isAssetMapperAvailable($container)) {
            $container->prependExtensionConfig('framework', [
                'asset_mapper' => [
                    'paths' => [
                        __DIR__.'/../../assets/controllers' => 'tito10047/altcha-bundle',
                    ],
                ],
            ]);
        }
    }

    private function isAssetMapperAvailable(ContainerBuilder $container): bool
    {
        if (!interface_exists(AssetMapperInterface::class)) {
            return false;
        }

        // check that FrameworkBundle 6.3 or higher is installed
        $bundlesMetadata = $container->getParameter('kernel.bundles_metadata');
        if (!isset($bundlesMetadata['FrameworkBundle'])) {
            return false;
        }

        return is_file($bundlesMetadata['FrameworkBundle']['path'].'/Resources/config/asset_mapper.php');
    }
}
