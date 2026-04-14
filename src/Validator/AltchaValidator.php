<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Validator;

use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\Challenge;
use AltchaOrg\Altcha\ChallengeParameters;
use AltchaOrg\Altcha\Payload;
use AltchaOrg\Altcha\Solution;
use AltchaOrg\Altcha\VerifySolutionOptions;
use Random\RandomException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Tito10047\AltchaBundle\Service\DriverKeyProviderInterface;

final class AltchaValidator extends ConstraintValidator
{
    public function __construct(
        private readonly bool $enable,
        private readonly string $hmacSignature,
        private readonly ?string $hmacKeySignature,
        private readonly RequestStack $requestStack,
        private readonly DriverKeyProviderInterface $driverKeyProvider,
        private readonly ?RateLimiterFactory $rateLimiter = null,
    ) {
    }

	/**
	 * Checks if the passed value is valid.
	 *
	 * @param mixed      $value      The value that should be validated
	 * @param Constraint $constraint The constraint for the validation
	 *
	 * @throws RandomException
	 */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (false === $this->enable) {
            return;
        }
		$request = $this->requestStack->getCurrentRequest();

        if (!$value) {
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


		$challenge = new Challenge(
			parameters: ChallengeParameters::fromArray($payload["challenge"]["parameters"] ?? []),
			signature: $payload["challenge"]["signature"] ?? "",
		);

        if ($this->rateLimiter !== null) {
            $key = hash('sha256', $challenge->signature);
            $limit = $this->rateLimiter->create($key)->consume();
            if (!$limit->isAccepted()) {
                $this->context->buildViolation($constraint->rateLimitMessage)
                    ->addViolation();

                return;
            }
        }

	    $result    = (new Altcha(
			hmacSignatureSecret: $this->hmacSignature,
			hmacKeySignatureSecret: $this->hmacKeySignature,
		))->verifySolution(new VerifySolutionOptions(
			payload: new Payload($challenge, new Solution(
				counter: $payload["solution"]["counter"]??10,
				derivedKey: $payload["solution"]["derivedKey"]??"",
				time: $payload["solution"]["time"]??0,
			)),
			algorithm: $this->driverKeyProvider->getAlgorithm(),
		));

        if (!$result->verified) {
            $this->context->buildViolation($constraint->message)
                ->addviolation();
        }
    }
}
