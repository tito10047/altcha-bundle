<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\Controller;

use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\ChallengeOptions;
use Huluti\AltchaBundle\Service\ChallengeResolverInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HulutiAltchaChallengeController extends AbstractController
{
    public function __construct(private readonly ChallengeResolverInterface $challengeOptionResolver)
    {
    }

    #[Route('/huluti_altcha/challenge', name: 'huluti_altcha_challenge')]
    public function challenge(): JsonResponse
    {

        return new JsonResponse($this->challengeOptionResolver->getChallenge());
    }
}
