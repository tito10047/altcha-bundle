<?php
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 11. 2. 2025
 * Time: 19:43
 */

namespace Huluti\AltchaBundle\Tests\Validator;

use AltchaOrg\Altcha\Algorithm;
use AltchaOrg\Altcha\ChallengeOptions;
use Huluti\AltchaBundle\Validator\Altcha;
use Symfony\Component\HttpFoundation\InputBag;

class AltchaValidatorTest extends \PHPUnit\Framework\TestCase
{
    public function testAltchaNotInRequest()
    {
        $requestStack = $this->createMock(\Symfony\Component\HttpFoundation\RequestStack::class);
        $request = $this->createMock(\Symfony\Component\HttpFoundation\Request::class);
        $requestStack->method('getCurrentRequest')->willReturn($request);
        $request->request = new InputBag([
            "altcha" => null
        ]);

        $validator = new \Huluti\AltchaBundle\Validator\AltchaValidator(true, 'key', $requestStack);
        $constraint = new Altcha();
        $context = $this->createMock(\Symfony\Component\Validator\Context\ExecutionContextInterface::class);
        $validator->initialize($context);

        $context->expects($this->once())->method('buildViolation');

        $validator->validate(null, $constraint);
    }

    public function testAltchaNotEncodedPropertly():void
    {
        $requestStack = $this->createMock(\Symfony\Component\HttpFoundation\RequestStack::class);
        $request = $this->createMock(\Symfony\Component\HttpFoundation\Request::class);
        $requestStack->method('getCurrentRequest')->willReturn($request);
        $request->request = new InputBag([
            "altcha" => 'not base64 encoded'
        ]);

        $validator = new \Huluti\AltchaBundle\Validator\AltchaValidator(true, 'key', $requestStack);
        $constraint = new Altcha();
        $context = $this->createMock(\Symfony\Component\Validator\Context\ExecutionContextInterface::class);
        $validator->initialize($context);

        $this->expectException(\JsonException::class);

        $validator->validate(null, $constraint);

    }

    public function testAltchaNotValid():void
    {
        $requestStack = $this->createMock(\Symfony\Component\HttpFoundation\RequestStack::class);
        $request = $this->createMock(\Symfony\Component\HttpFoundation\Request::class);
        $requestStack->method('getCurrentRequest')->willReturn($request);
        $request->request = new InputBag([
            "altcha" => base64_encode(json_encode(['solution' => 'not valid']))
        ]);

        $validator = new \Huluti\AltchaBundle\Validator\AltchaValidator(true, 'key', $requestStack);
        $constraint = new Altcha();
        $context = $this->createMock(\Symfony\Component\Validator\Context\ExecutionContextInterface::class);
        $validator->initialize($context);

        $context->expects($this->once())->method('buildViolation');

        $validator->validate(null, $constraint);
    }

    public function testAltchaIsValid():void
    {
        $options = new ChallengeOptions([
            'hmacKey' => 'test-key',
            'maxNumber' => 100000,
            'number' => 10,
            'expires' => (new \DateTime())->modify("+1 day"),
        ]);

        $challenge = (array)\AltchaOrg\Altcha\Altcha::createChallenge($options);
        $challenge['number'] = 10;


        $requestStack = $this->createMock(\Symfony\Component\HttpFoundation\RequestStack::class);
        $request = $this->createMock(\Symfony\Component\HttpFoundation\Request::class);
        $requestStack->method('getCurrentRequest')->willReturn($request);
        $request->request = new InputBag([
            "altcha" => base64_encode(json_encode($challenge))
        ]);

        $validator = new \Huluti\AltchaBundle\Validator\AltchaValidator(true, 'test-key', $requestStack);
        $constraint = new Altcha();
        $context = $this->createMock(\Symfony\Component\Validator\Context\ExecutionContextInterface::class);
        $validator->initialize($context);

        $context->expects($this->never())->method('buildViolation');

        $validator->validate(null, $constraint);

    }
}