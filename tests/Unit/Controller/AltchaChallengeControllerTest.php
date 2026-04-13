<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Tests\Unit\Controller;

use AltchaOrg\Altcha\Algorithm\Pbkdf2;
use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\CreateChallengeOptions;
use AltchaOrg\Altcha\Payload;
use AltchaOrg\Altcha\SolveChallengeOptions;
use AltchaOrg\Altcha\VerifySolutionOptions;
use Tito10047\AltchaBundle\Controller\AltchaChallengeController;
use Tito10047\AltchaBundle\Service\ChallengeResolverInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class AltchaChallengeControllerTest extends KernelTestCase
{

    public function testChallenge()
    {
        $algorithm = new Pbkdf2();
        $challengeOptions = new CreateChallengeOptions(
            algorithm: $algorithm,
            cost: 100,
            counter: 12345,
            expiresAt: (new \DateTime())->modify("+1 day"),
        );
        $altcha = new Altcha('test-key', 'test-sig-key');
        $challenge = $altcha->createChallenge($challengeOptions);

        $challengeResolver = $this->createMock(ChallengeResolverInterface::class);
        $challengeResolver->method('getChallenge')->willReturn($challenge);

        $controller = new AltchaChallengeController($challengeResolver);

        $response = $controller->challenge();

        $this->assertInstanceOf(JsonResponse::class, $response);

        $payloadData = json_decode($response->getContent(), true);

        $solution = $altcha->solveChallenge(new SolveChallengeOptions(
            algorithm: $algorithm,
            challenge: $challenge,
        ));

        $result = $altcha->verifySolution(new VerifySolutionOptions(
            payload: new Payload($challenge, $solution),
            algorithm: $algorithm,
        ));

        $this->assertTrue($result->verified);
    }

}
