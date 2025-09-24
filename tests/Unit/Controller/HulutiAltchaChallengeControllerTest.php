<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Tests\Controller;

use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\ChallengeOptions;
use Tito10047\AltchaBundle\Controller\AltchaChallengeController;
use Tito10047\AltchaBundle\Service\ChallengeResolverInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class AltchaChallengeControllerTest extends KernelTestCase
{

    public function testChallenge()
    {
        $challengeOptions = new ChallengeOptions(
            maxNumber: 100000,
            expires: (new \DateTime())->modify("+1 day"),
        );
        $altcha = new Altcha('test-key');
        $challenge = $altcha->createChallenge($challengeOptions);

        $challengeResolver = $this->createMock(ChallengeResolverInterface::class);
        $challengeResolver->method('getChallenge')->willReturn($challenge);

        $controller = new AltchaChallengeController($challengeResolver);

        $response = $controller->challenge();

        $this->assertInstanceOf(JsonResponse::class, $response);

        $payload = json_decode($response->getContent(), true);

        $payload['number'] = $challengeOptions->number;

        $isValid = $altcha->verifySolution($payload);

        $this->assertTrue($isValid);
    }

}
