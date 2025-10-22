<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Tests\Unit\Validator;

use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\ChallengeOptions;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Tito10047\AltchaBundle\Validator\AltchaValidator;

class AltchaValidatorValueTest extends TestCase
{
    public function testAltchaNotInRequest()
    {
        $requestStack = $this->createMock(RequestStack::class);

        $validator = new AltchaValidator(true, 'key', $requestStack);
        $constraint = new \Tito10047\AltchaBundle\Validator\Altcha();
        $context = $this->createMock(ExecutionContextInterface::class);
        $validator->initialize($context);

        $context->expects($this->once())->method('buildViolation');

        $validator->validate(null, $constraint);
    }

    public function testAltchaNotEncodedPropertly():void
    {
        $requestStack = $this->createMock(RequestStack::class);

        $validator = new AltchaValidator(true, 'key', $requestStack);
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

        $validator = new AltchaValidator(true, 'key', $requestStack);
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

        $validator = new AltchaValidator(true, 'test-key', $requestStack);
        $constraint = new \Tito10047\AltchaBundle\Validator\Altcha();
        $context = $this->createMock(ExecutionContextInterface::class);
        $validator->initialize($context);

        $context->expects($this->never())->method('buildViolation');

        $validator->validate($value, $constraint);

    }
}