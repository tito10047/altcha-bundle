<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Controller;

use Tito10047\AltchaBundle\Service\ChallengeResolverInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class AltchaChallengeController extends AbstractController
{
    public function __construct(private readonly ChallengeResolverInterface $challengeOptionResolver)
    {
    }

    #[Route('/altcha/challenge', name: 'altcha_challenge')]
    public function challenge(): JsonResponse
    {
        return new JsonResponse($this->challengeOptionResolver->getChallenge());
    }
}
