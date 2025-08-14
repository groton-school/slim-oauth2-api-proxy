<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken;

use League\OAuth2\Client\Token as League;

class AccessToken extends League\AccessToken
{
    /**
     * Some APIs are parsimonious about refresh tokens and only give the
     * out on the first authentication. Preserve that first refresh token
     * by merging it into any new access tokens.
     */
    public static function merge(League\AccessToken $a, League\AccessToken $b): AccessToken
    {
        if ($a->getExpires() > $b->getExpires()) {
            $temp = $a;
            $a = $b;
            $b = $temp;
        }
        $result = clone $b;
        if (!$b->getRefreshToken() && $a->getRefreshToken()) {
            $result->setRefreshToken($a->getRefreshToken());
        }
        return $result;
    }
}
