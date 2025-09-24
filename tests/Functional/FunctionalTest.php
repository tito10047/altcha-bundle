<?php

namespace Tito10047\AltchaBundle\Tests\Functional;


class FunctionalTest  extends PantherTestCase {
	public function testAssetMapper(): void
	{
		$client = static::createPantherAssetMapperClient();
		$client->request('GET', '/');
		$client->waitFor('.altcha-main',3);

		$this->assertPageTitleContains('Welcome!');
		$this->assertSelectorTextContains('.altcha-label', 'I\'m not a robot');

		$this->assertSelectorIsVisible('.altcha-main');
		$this->assertSelectorAttributeContains('body>form>div>div>div', 'data-controller', 'tito10047--altcha-bundle--altcha');
	}
	public function testWebpack(): void
	{
		$client = static::createPantherWebpackClient();
		$client->request('GET', '/');
		$client->waitFor('.altcha-main',3);

		$this->assertPageTitleContains('Welcome!');
		$this->assertSelectorTextContains('.altcha-label', 'I\'m not a robot');

		$this->assertSelectorIsVisible('.altcha-main');
		$this->assertSelectorAttributeContains('body>form>div>div>div', 'data-controller', 'tito10047--altcha-bundle--altcha');
	}
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