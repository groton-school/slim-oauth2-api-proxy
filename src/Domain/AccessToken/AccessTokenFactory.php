<?php

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\Modifier\SameSite;
use Dflydev\FigCookies\SetCookie;
use GrotonSchool\Slim\SPA\OAuth2\Client\SettingsInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\RequestInterface;

class AccessTokenFactory
{
    public function __construct(private SettingsInterface $settings) {}

    public function fromRequestCookie(RequestInterface $request): AccessToken
    {
        return new AccessToken(
            json_decode(
                FigRequestCookies::get(
                    $request,
                    $this->settings->getOAuth2TokensCookieName()
                )->getValue(),
                true
            )
        );
    }

    public function fromLeagueAccessToken(AccessTokenInterface $token)
    {
        return new AccessToken($token->jsonSerialize());
    }

    public function toCookie(AccessTokenInterface $token): SetCookie
    {
        return SetCookie::createRememberedForever(
            $this->settings->getOAuth2TokensCookieName()
        )
            ->withValue(json_encode($token))
            ->withPath('/')
            ->withSameSite(SameSite::none())
            ->withSecure()
            ->withPartitioned();
    }
}
