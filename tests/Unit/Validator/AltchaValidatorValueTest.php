<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Tests\Unit\Validator;

use AltchaOrg\Altcha\V1\Altcha;
use AltchaOrg\Altcha\V1\ChallengeOptions;
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
    private function createValidator(RequestStack $requestStack): AltchaValidator
    {
        return new AltchaValidator(
            true,
            'key',
            'key-sig',
            $requestStack,
            $this->createMock(DriverKeyProviderInterface::class),
            $this->createMock(SolveChallengeResolverInterface::class),
            $this->createMock(ChallengeResolverInterface::class)
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

        $validator = $this->createValidator($requestStack);
        $constraint = new \Tito10047\AltchaBundle\Validator\Altcha();
        $context = $this->createMock(ExecutionContextInterface::class);
        $validator->initialize($context);

        $context->expects($this->once())->method('buildViolation');

        $validator->validate($value, $constraint);
    }

    public function testAltchaIsValid():void
    {
        $options = new ChallengeOptions(
            maxNumber: 100000,
            expires: (new DateTime())->modify("+1 day"),
        );

        $challenge = (array) (new Altcha('test-key'))->createChallenge($options);
        $challenge['number'] = $options->number;


        $requestStack = $this->createMock(RequestStack::class);
		$value    = base64_encode(json_encode($challenge));

        $validator = $this->createValidator($requestStack);
        $constraint = new \Tito10047\AltchaBundle\Validator\Altcha();
        $context = $this->createMock(ExecutionContextInterface::class);
        $validator->initialize($context);

        $context->expects($this->never())->method('buildViolation');

        $validator->validate($value, $constraint);

    }
}