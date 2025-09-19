<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\Modifier\SameSite;
use Dflydev\FigCookies\SetCookie;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AccessTokenCookie extends AbstractAccessTokenRepository
{
    public function __construct(
        private ProviderInterface $provider
    ) {}

    public function getCookieName()
    {
        return $this->provider->getSlug() . '-token';
    }

    public function getToken(RequestInterface $request): ?AccessToken
    {
        $storedToken = FigRequestCookies::get(
            $request,
            $this->getCookieName()
        )->getValue();
        if ($storedToken) {
            return $this->refresh(new AccessToken(
                json_decode(
                    $storedToken,
                    true
                )
            ));
        }
        return null;
    }

    public function setToken(
        AccessToken $token,
        RequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        return FigResponseCookies::set($response, $this->toCookie($token));
    }

    public function deleteToken(
        RequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        return FigResponseCookies::set($response, $this->toCookie(null));
    }

    protected function toCookie(?AccessToken $token): SetCookie
    {
        $cookie = SetCookie::createRememberedForever(
            $this->getCookieName()
        )
            ->withValue(json_encode($token))
            ->withPath('/')
            ->withHttpOnly()
            ->withSameSite(SameSite::none())
            ->withSecure()
            ->withPartitioned();
        if (!$token) {
            $cookie = $cookie->expire();
        }
        return $cookie;
    }
}
