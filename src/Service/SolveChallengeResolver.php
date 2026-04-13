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

use AltchaOrg\Altcha\Algorithm\Pbkdf2;
use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\Solution;
use AltchaOrg\Altcha\SolveChallengeOptions;

class SolveChallengeResolver implements SolveChallengeResolverInterface{
	public function __construct(
		private readonly ChallengeResolverInterface $challengeResolver,
		private readonly string $hmacSignature,
		private readonly ?string $hmacKeySignature,
		private readonly float $timeout
	) {
	}

	public function solveChallenge(): Solution {

		$pbkdf2 = new Pbkdf2();
		$options = new SolveChallengeOptions(
			challenge: $this->challengeResolver->getChallenge(),
			algorithm: $pbkdf2,
			timeout: $this->timeout,
		);

		return (new Altcha(
			hmacSignatureSecret: $this->hmacSignature,
			hmacKeySignatureSecret: $this->hmacKeySignature,
		))->solveChallenge($options);
	}
}