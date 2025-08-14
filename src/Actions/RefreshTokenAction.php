<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Actions;

use Dflydev\FigCookies\FigResponseCookies;
use GrotonSchool\Slim\Norms\AbstractAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken\AccessToken;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken\AccessTokenFactory;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderRepositoryInterface;
use GrotonSchool\Slim\OAuth2\APIProxy\SettingsInterface;
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
