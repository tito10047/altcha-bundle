<?php

namespace Tito10047\AltchaBundle\Tests\Functional;

use Symfony\Component\Panther\WebTestAssertionsTrait;
use Tito10047\AltchaBundle\Tests\App\KernelTestCase;

abstract class PantherTestCase extends KernelTestCase
{
	use WebTestAssertionsTrait;

	public const CHROME = 'chrome';
	public const FIREFOX = 'firefox';
	public const SELENIUM = 'selenium';

	protected function tearDown(): void
	{
		$this->doTearDown();
	}

	private function doTearDown(): void
	{
		parent::tearDown();
		$this->takeScreenshotIfTestFailed();
		self::getClient(null);
	}

	protected static function createPantherWebpackClient(): \Symfony\Component\Panther\Client {
		return static::createPantherClient([
			'webServerDir'=>__DIR__.'/../App/Webpack/public',
		]);
	}

	protected static function createPantherTwigClient(): \Symfony\Component\Panther\Client {
		return static::createPantherClient([
			'webServerDir'=>__DIR__.'/../App/Twig/public',
		]);
	}

	protected static function createPantherAssetMapperClient(): \Symfony\Component\Panther\Client {
		return static::createPantherClient([
			'webServerDir'=>__DIR__.'/../App/AssetMapper/public',
		]);
	}

}