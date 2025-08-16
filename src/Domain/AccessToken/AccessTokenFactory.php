<?php

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\Modifier\SameSite;
use Dflydev\FigCookies\SetCookie;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\RequestInterface;

class AccessTokenFactory
{
    public function __construct(private ProviderInterface $provider) {}

    public function fromRequest(RequestInterface $request): ?AccessToken
    {
        $storedToken = FigRequestCookies::get(
            $request,
            $this->provider->getCookieName()
        )->getValue();
        if ($storedToken) {
            return $this->refresh(
                new AccessToken(
                    json_decode(
                        $storedToken,
                        true
                    )
                )
            );
        }
        return null;
    }

    private function refresh(?AccessToken $token)
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


    public function toCookie(?AccessTokenInterface $token): SetCookie
    {
        $cookie = SetCookie::createRememberedForever(
            $this->provider->getCookieName()
        )
            ->withValue(json_encode($token))
            ->withPath('/')
            ->withHttpOnly()
            ->withSameSite(SameSite::none())
            ->withSecure()
            ->withPartitioned();
        if (!$token) {
            $cookie = $cookie->withValue("")
                ->withExpires();
        }
        return $cookie;
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
