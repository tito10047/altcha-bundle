<?php

namespace Functional;


use Tito10047\AltchaBundle\Tests\Functional\PantherTestCase;

class OverlayTest  extends PantherTestCase {
	public function testOverlay(): void
	{
		$client = static::createPantherClient('Overlay');
		$client->request('GET', '/');
		$client->waitFor('.altcha-main',3);

		$this->assertPageTitleContains('Welcome!');
		$this->assertSelectorExists('label[for^="altcha-checkbox-"]');
		$client->submitForm("Submit");
		$client->waitFor('.altcha-overlay-content',3);
		$client->wait(1);

		$this->assertSelectorTextContains('.altcha-overlay-content','Verifying you are human...');

		$this->assertSelectorExists("#script-altcha","Altcha script need be included");
		$this->assertSelectorExists("#script-altcha-i18n","Altcha i18n script need be included");

	}
}