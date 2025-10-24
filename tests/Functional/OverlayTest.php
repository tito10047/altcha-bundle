<?php

namespace Functional;


use Tito10047\AltchaBundle\Tests\Functional\PantherTestCase;

class OverlayTest  extends PantherTestCase {
	public function testTwig(): void
	{
		$client = static::createPantherTwigClient();
		$client->request('GET', '/');
		$client->waitFor('.altcha-main',3);

		$this->assertPageTitleContains('Welcome!');
		$this->assertSelectorNotExists('.altcha-label');

		$this->assertSelectorExists("#script-altcha","Altcha script need be included");
		$this->assertSelectorExists("#script-altcha-i18n","Altcha i18n script need be included");

	}
}