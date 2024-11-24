<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\Validator;

use AltchaOrg\Altcha\Altcha;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class AltchaValidator extends ConstraintValidator
{
    private bool $enable;
    private string $hmacKey;

    private RequestStack $requestStack;

    public function __construct(
        bool $enable,
        string $hmacKey,
        RequestStack $requestStack,
    ) {
        $this->enable = $enable;
        $this->hmacKey = $hmacKey;
        $this->requestStack = $requestStack;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint): void
    {
        if ($this->enable === false) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();
        $altchaEncoded = $request->request->get('altcha');
        $altchaJson = base64_decode($altchaEncoded, true);
        $payload = json_decode($altchaJson, true);

        // TODO: verify each line above

        if (!Altcha::verifySolution($payload, $this->hmacKey, true)) {
            $this->context->buildViolation($constraint->message)
                ->addviolation();
        }
    }
}
