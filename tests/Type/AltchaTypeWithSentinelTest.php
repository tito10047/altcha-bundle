<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\Tests\Type;

use Generator;
use Huluti\AltchaBundle\Type\AltchaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AltchaTypeWithSentinelTest extends AltchaTypeTest
{
    public function setUp(): void
    {
        $this->altchaType = new AltchaType(
            enable: true,
            floating: true,
            useStimulus: true,
            useAssetMapper: false,
            hideLogo: true,
            hideFooter: true,
            jsPath: "test",
            useSentinel: true,
            challengeUrl: 'http://localhost',
        );
    }
}