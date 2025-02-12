<?php
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 11. 2. 2025
 * Time: 19:07
 */

namespace Huluti\AltchaBundle\Service;

use AltchaOrg\Altcha\Challenge;

interface ChallengeResolverInterface
{
    public function getChallenge(): Challenge;
}