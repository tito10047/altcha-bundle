<?php

namespace Functional;


use Tito10047\AltchaBundle\Tests\Functional\PantherTestCase;

class LiveComponentTest  extends PantherTestCase {
	public function testLiveComponent(): void
	{
		$client = static::createPantherClient('LiveComponent');
		$client->request('GET', '/');
		$client->waitFor('.altcha-main',3);

		$this->assertPageTitleContains('Welcome!');
		$this->assertSelectorTextContains('.altcha-label', 'I\'m not a robot');

		//TODO: add more tests

		$this->assertSelectorNotExists("#script-altcha","Altcha script need not included");
		$this->assertSelectorNotExists("#script-altcha-i18n","Altcha i18n script need not included");

	}
}