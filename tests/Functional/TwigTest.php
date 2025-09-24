<?php

namespace Functional;


use Tito10047\AltchaBundle\Tests\Functional\PantherTestCase;

class TwigTest  extends PantherTestCase {
	public function testTwig(): void
	{
		$client = static::createPantherTwigClient();
		$client->request('GET', '/');
		$client->waitFor('.altcha-main',3);

		$this->assertPageTitleContains('Welcome!');
		$this->assertSelectorTextContains('.altcha-label', 'I\'m not a robot');

		$this->assertSelectorIsVisible('.altcha-main');
		$this->assertSelectorAttributeContains('body>form>div>div>div', 'data-controller', 'tito10047--altcha-bundle--altcha');
	}
}