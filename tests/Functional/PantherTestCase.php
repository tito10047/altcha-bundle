<?php

namespace Tito10047\AltchaBundle\Tests\Functional;

use Symfony\Component\Panther\WebTestAssertionsTrait;
use Tito10047\AltchaBundle\Tests\App\KernelTestCase;

abstract class PantherTestCase extends KernelTestCase
{
	use WebTestAssertionsTrait {
		createPantherClient as createPantherClientTrait;
	}

	public const CHROME = 'chrome';
	public const FIREFOX = 'firefox';
	public const SELENIUM = 'selenium';

	protected function tearDown(): void
	{
		$this->doTearDown();
		try {
			static::stopWebServer();
		} catch (\Throwable $e) {
			// ignore if already stopped
		}
	}

	private function doTearDown(): void
	{
		parent::tearDown();
		$this->takeScreenshotIfTestFailed();
		self::getClient(null);
	}

	public static function createPantherClient(string $app): \Symfony\Component\Panther\Client {
		return self::createPantherClientTrait([
			'webServerDir'=>__DIR__."/../App/{$app}/public",
		],[
			'configDir'=>"{$app}/config",
		]);
	}


}