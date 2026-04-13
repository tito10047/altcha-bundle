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
use Tito10047\AltchaBundle\Service\ChallengeResolverInterface;
use Tito10047\AltchaBundle\Service\DriverKeyProviderInterface;
use Tito10047\AltchaBundle\Service\SolveChallengeResolverInterface;
use Tito10047\AltchaBundle\Validator\AltchaValidator;

class AltchaValidatorValueTest extends TestCase
{
    private function createValidator(
        RequestStack $requestStack,
        ?DriverKeyProviderInterface $driverKeyProvider = null,
        ?SolveChallengeResolverInterface $solveChallengeResolver = null,
        ?ChallengeResolverInterface $challengeResolver = null
    ): AltchaValidator
    {
        return new AltchaValidator(
            true,
            'key',
            'key-sig',
            $requestStack,
            $driverKeyProvider ?? $this->createMock(DriverKeyProviderInterface::class),
            $solveChallengeResolver ?? $this->createMock(SolveChallengeResolverInterface::class),
            $challengeResolver ?? $this->createMock(ChallengeResolverInterface::class)
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

        $challenge = new Challenge(new ChallengeParameters(
            algorithm: 'SHA-256',
            nonce: 'nonce',
            salt: 'salt',
            cost: 100,
            keyLength: 32,
            keyPrefix: '00'
        ), 'sig');
        $solution = new Solution(1, 'derived-key', 0.1);

        $solveChallengeResolver = $this->createMock(SolveChallengeResolverInterface::class);
        $solveChallengeResolver->method('solveChallenge')->willReturn($solution);

        $challengeResolver = $this->createMock(ChallengeResolverInterface::class);
        $challengeResolver->method('getChallenge')->willReturn($challenge);

        $validator = $this->createValidator($requestStack, null, $solveChallengeResolver, $challengeResolver);
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

        $solveChallengeResolver = $this->createMock(SolveChallengeResolverInterface::class);
        $solveChallengeResolver->method('solveChallenge')->willReturn($solution);

        $challengeResolver = $this->createMock(ChallengeResolverInterface::class);
        $challengeResolver->method('getChallenge')->willReturn($challenge);

        $validator = $this->createValidator($requestStack, $driverKeyProvider, $solveChallengeResolver, $challengeResolver);
        $constraint = new \Tito10047\AltchaBundle\Validator\Altcha();
        $context = $this->createMock(ExecutionContextInterface::class);
        $validator->initialize($context);

        $context->expects($this->never())->method('buildViolation');

        $value = base64_encode(json_encode($challenge));
        $validator->validate($value, $constraint);
    }
}