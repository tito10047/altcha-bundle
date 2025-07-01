<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\Validator;

use AltchaOrg\Altcha\Altcha;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class AltchaSentinelValidator extends ConstraintValidator implements LoggerAwareInterface
{
    private ?LoggerInterface $logger = null;

    public function __construct(
        private readonly bool $enable,
        private readonly string $apiKey,
        private readonly string $verifySignatureUrl,
        private readonly HttpClientInterface $httpClient,
        private readonly RequestStack $requestStack,
        private readonly string $hmacKey,
    ) {
    }

    public function setLogger(?LoggerInterface $logger = null): void
    {
        $this->logger = $logger;
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

        $response = $this->httpClient->request('POST', $this->verifySignatureUrl, [
            'json' => ['payload' => $value],
        ]);
        try {
            $responseContent = $response->toArray();
        } catch (TransportExceptionInterface|DecodingExceptionInterface|HttpExceptionInterface $e) {
            if ($this->logger) {
                $this->logger->error(sprintf(
                    'Encountered a %s exception while querying %s endpoint: details: "%s", will use local validation instead.',
                    get_class($e),
                    $this->verifySignatureUrl,
                    $e->getMessage()
                ));
            }

            $altchaJson = base64_decode($value, true);
            if (!is_string($altchaJson)) {
                $this->context->buildViolation($constraint->message)
                    ->addviolation();

                return;
            }
            $payload = json_decode($altchaJson, true, 512, JSON_THROW_ON_ERROR);
            if (!(new Altcha($this->hmacKey))->verifySolution($payload, true)) {
                $this->context->buildViolation($constraint->message)
                    ->addviolation();
            }

            return;
        }

        if (
            array_key_exists('verified', $responseContent)
            && true === $responseContent['verified']
            && array_key_exists('apiKey', $responseContent)
            && $this->apiKey === $responseContent['apiKey']
        ) {
            return;
        }
        if ($this->logger) {
            $this->logger->warning(sprintf(
                'Sentinel server refused the verification, received "%s"; it may be due to a mismatch of api key.',
                json_encode($responseContent)
            ));
        }

        $this->context->buildViolation($constraint->message)
            ->addviolation();
    }
}
