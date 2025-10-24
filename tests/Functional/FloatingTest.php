<?php

namespace Functional;


use Tito10047\AltchaBundle\Tests\Functional\PantherTestCase;

class FloatingTest  extends PantherTestCase {
	public function testFloating(): void
	{
		$client = static::createPantherClient('Floating');
		$client->request('GET', '/');
		$client->waitFor('.altcha-main',3);

		$this->assertPageTitleContains('Welcome!');
		$this->assertSelectorExists('.altcha-label');
		$this->assertSelectorIsNotVisible('.altcha-label');

		$this->assertSelectorExists("#script-altcha","Altcha script need be included");
		$this->assertSelectorExists("#script-altcha-i18n","Altcha i18n script need be included");

	}
}