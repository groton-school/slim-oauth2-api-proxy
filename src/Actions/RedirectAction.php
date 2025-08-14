<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Actions;

use Dflydev\FigCookies\FigResponseCookies;
use GrotonSchool\Slim\Norms\AbstractAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken\AccessTokenFactory;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderFactory;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class RedirectAction extends AbstractAction
{
    public function __construct(
        private ProviderFactory $providerFactory,
        private SessionInterface $session,
    ) {}

    protected function invokeHook(
        ServerRequest $request,
        Response $response,
        array $args = []
    ): ResponseInterface {
        $provider = $this->providerFactory->fromSession($this->session);
        $state = $request->getQueryParam('state');
        if (!$provider || empty($state) || $state !== $this->session->get(AuthorizeAction::STATE)) {
            return $response->withStatus(400);
        }
        $accessTokenFactory = new AccessTokenFactory($provider);
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $request->getQueryParam('code')
        ]);
        return FigResponseCookies::set(
            $response->withRedirect(
                $provider->getAuthorizedRedirect()
            ),
            $accessTokenFactory->toCookie($token)
        );
    }
}
