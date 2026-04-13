<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Validator;

use AltchaOrg\Altcha\Algorithm\Pbkdf2;
use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\Payload;
use AltchaOrg\Altcha\VerifySolutionOptions;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Tito10047\AltchaBundle\Service\ChallengeResolverInterface;
use Tito10047\AltchaBundle\Service\DriverKeyProviderInterface;
use Tito10047\AltchaBundle\Service\SolveChallengeResolverInterface;

final class AltchaValidator extends ConstraintValidator
{
    public function __construct(
        private readonly bool $enable,
        private readonly string $hmacSignature,
        private readonly string $hmacKeySignature,
        private readonly RequestStack $requestStack,
        private readonly DriverKeyProviderInterface $driverKeyProvider,
		private readonly SolveChallengeResolverInterface $solveChallengeResolver,
		private readonly ChallengeResolverInterface $challengeOptionResolver
    ) {
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (false === $this->enable) {
            return;
        }

        if (!$value) {
            $request = $this->requestStack->getCurrentRequest();
            $value = $request?->request->get('altcha');
        }

        if (!$value) {
            $request = $this->requestStack->getMainRequest();
            $value = $request?->request->get('altcha');
        }

        if (!is_string($value)) {
            $this->context->buildViolation($constraint->message)
                ->addviolation();

            return;
        }

        $altchaJson = base64_decode($value, true);
        if (!is_string($altchaJson)) {
            $this->context->buildViolation($constraint->message)
                ->addviolation();

            return;
        }
		try {
			$payload = json_decode($altchaJson, true, 512, JSON_THROW_ON_ERROR);
		}catch (\JsonException) {
			$this->context->buildViolation($constraint->message)
				->addviolation();
			return;
		}

		$solution = $this->solveChallengeResolver->solveChallenge();
		$challenge = $this->challengeOptionResolver->getChallenge();

		$result = (new Altcha(
			hmacSignatureSecret: $this->hmacSignature,
			hmacKeySignatureSecret: $this->hmacKeySignature,
		))->verifySolution(new VerifySolutionOptions(
			payload: new Payload($challenge, $solution),
			algorithm: $this->driverKeyProvider->getAlgorithm(),
		));

        if (!$result->verified) {
            $this->context->buildViolation($constraint->message)
                ->addviolation();
        }
    }
}
