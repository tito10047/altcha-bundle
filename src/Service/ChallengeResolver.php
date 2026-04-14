<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Service;

use AltchaOrg\Altcha\Algorithm\Pbkdf2;
use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\Challenge;
use AltchaOrg\Altcha\CreateChallengeOptions;
use Random\RandomException;

class ChallengeResolver implements ChallengeResolverInterface
{
    public function __construct(
		private readonly DriverKeyProviderInterface $driverKeyProvider,
		private readonly string $hmacSignature,
		private readonly ?string $hmacKeySignature,
		private readonly int $cost,
		private readonly int $counterMin,
		private readonly int $counterMax,
        private readonly string $expiresAt,
    ) {
    }

	/**
	 * @throws RandomException
	 */
	public function getChallenge(): Challenge
    {
        $options = new CreateChallengeOptions(
			algorithm: $this->driverKeyProvider->getAlgorithm(),
			cost: $this->cost,
			counter: random_int($this->counterMin, $this->counterMax),
			expiresAt: new \DateTimeImmutable($this->expiresAt),
        );


        return (new Altcha(
			hmacSignatureSecret: $this->hmacSignature,
			hmacKeySignatureSecret: $this->hmacKeySignature,
		))->createChallenge($options);
    }
}
