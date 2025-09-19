<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken;

use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\ServerRequest;

abstract class AbstractAccessTokenRepository
{
    public function __construct(private ProviderInterface $provider) {}

    abstract public function getToken(
        ServerRequest $request
    ): ?AccessToken;

    abstract public function setToken(
        AccessToken $token,
        ServerRequest $request,
        ResponseInterface $response
    ): ResponseInterface;

    abstract public function deleteToken(
        ServerRequest $request,
        ResponseInterface $response
    ): ResponseInterface;

    protected function refresh(?AccessToken $token)
    {
        if ($token && $token->hasExpired()) {
            $token = $this->merge(
                $token,
                $this->provider->getAccessToken(
                    'refresh_token',
                    [
                        'refresh_token' => $token->getRefreshToken()
                    ]
                )
            );
        }
        return $token;
    }

    /**
     * Some APIs are parsimonious about refresh tokens and only give the
     * out on the first authentication. Preserve that first refresh token
     * by merging it into any new access tokens.
     */
    public function merge(AccessToken $a, AccessToken $b): AccessToken
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
