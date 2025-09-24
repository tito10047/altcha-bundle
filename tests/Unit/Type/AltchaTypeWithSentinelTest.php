<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Tests\Type;

use Generator;
use Tito10047\AltchaBundle\Type\AltchaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AltchaTypeWithSentinelTest extends AltchaTypeTest
{
    public function setUp(): void
    {
		$router = $this->createMock(\Symfony\Component\Routing\RouterInterface::class);
        $this->altchaType = new AltchaType(
            enable: true,
            floating: true,
            useStimulus: true,
            useAssetMapper: false,
			useWebpack: false,
            hideLogo: true,
            hideFooter: true,
            jsPath: "test",
			i18nPath: "test",
            useSentinel: true,
            challengeUrl: 'http://localhost',
			router: $router,
        );
    }
}