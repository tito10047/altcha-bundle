<?php

namespace Tito10047\AltchaBundle\Tests\Functional;


class AssetMapperTest  extends PantherTestCase {
	public function testAssetMapper(): void
	{
		if (PHP_VERSION_ID >= 80100 && PHP_VERSION_ID < 80200) {
			$this->markTestSkipped('Skipped on PHP 8.1');
		}
		$client = static::createPantherAssetMapperClient();
		$client->request('GET', '/');
		$client->waitFor('.altcha-main',3);

		$this->assertPageTitleContains('Welcome!');
		$this->assertSelectorTextContains('.altcha-label', 'I\'m not a robot');

		$this->assertSelectorIsVisible('.altcha-main');
		$this->assertSelectorAttributeContains('body>form>div>div>div', 'data-controller', 'tito10047--altcha-bundle--altcha');
	}
}