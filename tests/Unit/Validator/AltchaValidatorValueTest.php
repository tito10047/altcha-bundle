<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Tests\Unit\Validator;

use AltchaOrg\Altcha\Algorithm\Pbkdf2;
use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\Challenge;
use AltchaOrg\Altcha\ChallengeParameters;
use AltchaOrg\Altcha\CreateChallengeOptions;
use AltchaOrg\Altcha\SolveChallengeOptions;
use AltchaOrg\Altcha\Solution;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Tito10047\AltchaBundle\Service\DriverKeyProviderInterface;
use Tito10047\AltchaBundle\Validator\AltchaValidator;

class AltchaValidatorValueTest extends TestCase
{
    private function createValidator(
        RequestStack $requestStack,
        ?DriverKeyProviderInterface $driverKeyProvider = null
    ): AltchaValidator
    {
        return new AltchaValidator(
            true,
            'key',
            'key-sig',
            $requestStack,
            $driverKeyProvider ?? $this->createMock(DriverKeyProviderInterface::class)
        );
    }

    public function testAltchaNotInRequest()
    {
        $requestStack = $this->createMock(RequestStack::class);

        $validator = $this->createValidator($requestStack);
        $constraint = new \Tito10047\AltchaBundle\Validator\Altcha();
        $context = $this->createMock(ExecutionContextInterface::class);
        $validator->initialize($context);

        $context->expects($this->once())->method('buildViolation');

        $validator->validate(null, $constraint);
    }

    public function testAltchaNotEncodedPropertly():void
    {
        $requestStack = $this->createMock(RequestStack::class);

        $validator = $this->createValidator($requestStack);
        $constraint = new \Tito10047\AltchaBundle\Validator\Altcha();
        $context = $this->createMock(ExecutionContextInterface::class);
        $validator->initialize($context);

		$context->expects($this->once())->method('buildViolation')
			->with($constraint->message);
        $validator->validate('not base64 encoded', $constraint);

    }

    public function testAltchaNotValid():void
    {
        $requestStack = $this->createMock(RequestStack::class);
		$value    = base64_encode(json_encode(['solution' => 'not valid']));

        $validator = $this->createValidator($requestStack, null);
        $constraint = new \Tito10047\AltchaBundle\Validator\Altcha();
        $context = $this->createMock(ExecutionContextInterface::class);
        $validator->initialize($context);

        $context->expects($this->once())->method('buildViolation');

        $validator->validate($value, $constraint);
    }

    public function testAltchaIsValid():void
    {
        $algorithm = new Pbkdf2();
        $options = new CreateChallengeOptions(
            algorithm: $algorithm,
            cost: 100,
            counter: 12345,
            expiresAt: (new DateTime())->modify("+1 day"),
        );

        $altcha = new Altcha('key', 'key-sig');
        $challenge = $altcha->createChallenge($options);
        $solution = $altcha->solveChallenge(new SolveChallengeOptions(
            algorithm: $algorithm,
            challenge: $challenge,
        ));

        $requestStack = $this->createMock(RequestStack::class);
        $driverKeyProvider = $this->createMock(DriverKeyProviderInterface::class);
        $driverKeyProvider->method('getAlgorithm')->willReturn($algorithm);

        $validator = $this->createValidator($requestStack, $driverKeyProvider);
        $constraint = new \Tito10047\AltchaBundle\Validator\Altcha();
        $context = $this->createMock(ExecutionContextInterface::class);
        $validator->initialize($context);

        $context->expects($this->never())->method('buildViolation');

        $value = base64_encode(json_encode([
            "challenge" => $challenge,
            "solution" => $solution,
        ]));
        $validator->validate($value, $constraint);
    }
}