<?php
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 11. 2. 2025
 * Time: 19:00
 */

namespace Huluti\AltchaBundle\Tests\Controller;

use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\ChallengeOptions;
use Huluti\AltchaBundle\Controller\HulutiAltchaChallengeController;
use Huluti\AltchaBundle\Service\ChallengeResolverInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class HulutiAltchaChallengeControllerTest extends KernelTestCase
{

    public function testChallenge()
    {

        $challengeOptions = new ChallengeOptions([
            'hmacKey' => 'test-key',
            'maxNumber' => 100000,
            'number' => 10,
            'expires' => (new \DateTime())->modify("+1 day"),
        ]);
        $challenge = Altcha::createChallenge($challengeOptions);

        $challengeResolver = $this->createMock(ChallengeResolverInterface::class);
        $challengeResolver->method('getChallenge')->willReturn($challenge);

        $controller = new HulutiAltchaChallengeController($challengeResolver);

        $response = $controller->challenge();

        $this->assertInstanceOf(JsonResponse::class, $response);

        $payload = json_decode($response->getContent(), true);

        $payload['number'] = 10;

        $isValid = Altcha::verifySolution($payload, 'test-key');

        $this->assertTrue($isValid);
    }

}
