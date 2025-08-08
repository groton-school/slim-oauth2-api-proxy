<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\SPA\OAuth2\Client\Domain\AccessToken;

use Dflydev\FigCookies\SetCookie;
use League\OAuth2\Client\Token as League;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\RequestInterface;

class AccessToken extends League\AccessToken
{
    private int $timestamp;

    public function __construct(array $options = [])
    {
        parent::__construct($options);
        $this->timestamp = $this->timestamp ?? time();
    }

    /**
     * Some APIs are parsimonious about refresh tokens and only give the
     * out on the first authentication. Preserve that first refresh token
     * by merging it into any new access tokens.
     */
    public static function merge(AccessToken $a, AccessToken $b): AccessToken
    {
        if ($a->getTimestamp() > $b->getTimestamp()) {
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

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function jsonSerialize()
    {
        return [
            ...parent::jsonSerialize(),
            'timestamp' => $this->timestamp
        ];
    }
}
