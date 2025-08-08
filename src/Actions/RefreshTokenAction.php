<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\SPA\OAuth2\Client\Actions;

use Dflydev\FigCookies\FigResponseCookies;
use GrotonSchool\Slim\Norms\AbstractAction;
use GrotonSchool\Slim\SPA\OAuth2\Client\Domain\AccessToken\AccessToken;
use GrotonSchool\Slim\SPA\OAuth2\Client\Domain\AccessToken\AccessTokenFactory;
use GrotonSchool\Slim\SPA\OAuth2\Client\SettingsInterface;
use League\OAuth2\Client\Provider\AbstractProvider;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Psr\Http\Message\ResponseInterface;

class RefreshTokenAction extends AbstractAction
{
    public function __construct(
        private AbstractProvider $provider,
        private AccessTokenFactory $tokenFactory,
        private SettingsInterface $settings
    ) {}

    protected function invokeHook(
        ServerRequest $request,
        Response $response,
        array $args = []
    ): ResponseInterface {
        $redirect = $request->getQueryParam('redirect') ??
            $this->settings->getOAuth2AuthenticatedRedirectUrl();
        $oldToken = $this->tokenFactory->fromRequestCookie($request);

        return FigResponseCookies::set(
            $response->withRedirect($redirect),
            $this->tokenFactory->toCookie(
                AccessToken::merge(
                    $oldToken,
                    $this->provider->getAccessToken('refresh_token', [
                        'refresh_token' => $oldToken['refresh_token']
                    ])
                )
            )
        );
    }
}
