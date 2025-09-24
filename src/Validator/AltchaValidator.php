<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Validator;

use AltchaOrg\Altcha\Altcha;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class AltchaValidator extends ConstraintValidator
{
    public function __construct(
        private readonly bool $enable,
        private readonly string $hmacKey,
        private readonly RequestStack $requestStack,
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
		}catch (\JsonException $e) {
			$this->context->buildViolation($constraint->message)
				->addviolation();
			return;
		}

        if (!(new Altcha($this->hmacKey))->verifySolution($payload, true)) {
            $this->context->buildViolation($constraint->message)
                ->addviolation();
        }
    }
}
