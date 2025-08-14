<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\SPA\OAuth2\Client\Actions;

use Dflydev\FigCookies\FigResponseCookies;
use GrotonSchool\Slim\Norms\AbstractAction;
use GrotonSchool\Slim\SPA\OAuth2\Client\Domain\AccessToken\AccessToken;
use GrotonSchool\Slim\SPA\OAuth2\Client\Domain\AccessToken\AccessTokenFactory;
use GrotonSchool\Slim\SPA\OAuth2\Client\Domain\Provider\ProviderRepositoryInterface;
use GrotonSchool\Slim\SPA\OAuth2\Client\SettingsInterface;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Psr\Http\Message\ResponseInterface;

class RefreshTokenAction extends AbstractAction
{
    public function __construct(
        private ProviderRepositoryInterface $providers,
        private AccessTokenFactory $tokenFactory,
        private SettingsInterface $settings
    ) {}

    protected function invokeHook(
        ServerRequest $request,
        Response $response,
        array $args = []
    ): ResponseInterface {
        $provider = $this->providers->find(
            $request->getQueryParam('host', ''),
            $request->getQueryParam('client_id', '')
        );
        $oldToken = $this->tokenFactory->fromRequestCookie($request);

        return FigResponseCookies::set(
            $response->withStatus(200),
            $this->tokenFactory->toCookie(
                AccessToken::merge(
                    $oldToken,
                    $this->tokenFactory->fromLeagueAccessToken(
                        $provider->getAccessToken('refresh_token', [
                            'refresh_token' => $oldToken->getRefreshToken()
                        ])
                    )
                )
            )
        );
    }
}
