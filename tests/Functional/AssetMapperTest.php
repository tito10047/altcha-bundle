<?php

namespace Tito10047\AltchaBundle\Tests\Functional;


class AssetMapperTest  extends PantherTestCase {
	public function testAssetMapper(): void
	{
		$client = static::createPantherClient('AssetMapper');
		$client->request('GET', '/');
		$client->waitFor('.altcha-main',3);

		$this->assertPageTitleContains('Welcome!');
		$this->assertSelectorTextContains('.altcha-label', 'I\'m not a robot');

		$this->assertSelectorNotExists("#script-altcha","Altcha script need not included");
		$this->assertSelectorNotExists("#script-altcha-i18n","Altcha i18n script need not included");

		$this->assertSelectorIsVisible('.altcha-main');
		$this->assertSelectorAttributeContains('body>form>div>div>div', 'data-controller', 'tito10047--altcha-bundle--altcha');
	}
}