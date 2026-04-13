<?php
/*
 * This file is part of the Progressive Image Bundle.
 *
 * (c) Jozef Môstka <https://github.com/tito10047/progressive-image-bundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tito10047\AltchaBundle\Service;

use AltchaOrg\Altcha\Algorithm\DeriveKeyInterface;
use AltchaOrg\Altcha\Algorithm\Pbkdf2;
use AltchaOrg\Altcha\HmacAlgorithm;

class DriverKeyProvider implements DriverKeyProviderInterface{

	public function __construct(
		private string $hmacAlgorithm
	) { }

	public function getAlgorithm(): DeriveKeyInterface {
		return new Pbkdf2(HmacAlgorithm::from($this->hmacAlgorithm));
	}
}