<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\Controller;

use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\ChallengeOptions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HulutiAltchaChallengeController extends AbstractController
{
    public function __construct(private readonly string $hmacKey)
    {
    }

    #[Route('/huluti_altcha/challenge', name: 'huluti_altcha_challenge')]
    public function challenge(): JsonResponse
    {
        $options = new ChallengeOptions([
            'hmacKey' => $this->hmacKey,
            'maxNumber' => 100000,
            'expires' => (new \DateTime())->modify('+15 minute'),
        ]);

        $challenge = Altcha::createChallenge($options);

        return new JsonResponse($challenge);
    }
}
