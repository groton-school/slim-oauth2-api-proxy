<?php

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\Modifier\SameSite;
use Dflydev\FigCookies\SetCookie;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\RequestInterface;

class AccessTokenFactory
{
    public function __construct(private ProviderInterface $provider) {}

    public function fromRequest(RequestInterface $request): AccessToken
    {
        return new AccessToken(
            json_decode(
                FigRequestCookies::get(
                    $request,
                    $this->provider->getCookieName()
                )->getValue(),
                true
            )
        );
    }

    public function toCookie(AccessTokenInterface $token): SetCookie
    {
        return SetCookie::createRememberedForever(
            $this->provider->getCookieName()
        )
            ->withValue(json_encode($token))
            ->withPath('/')
            ->withHttpOnly()
            ->withSameSite(SameSite::none())
            ->withSecure()
            ->withPartitioned();
    }
}
