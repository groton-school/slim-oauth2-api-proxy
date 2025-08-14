<?php

namespace GrotonSchool\Slim\OAuth2\APIProxy\Actions;

use GrotonSchool\Slim\Norms\AbstractAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderFactory;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class AuthorizeAction extends AbstractAction
{
    public const STATE = self::class . '::state';

    public function __construct(
        private ProviderFactory $providerFactory,
        private SessionInterface $session
    ) {}

    protected function invokeHook(
        ServerRequest $request,
        Response $response,
        array $args = []
    ): ResponseInterface {
        $provider = $this->providerFactory->fromRequest($request);
        if ($provider) {
            $authUrl = $provider->getAuthorizationUrl();
            $this->session->set(self::STATE, $provider->getState());
            $this->providerFactory->toSession($provider, $this->session);
            return $response->withRedirect($authUrl);
        } else {
            return $response->withStatus(400);
        }
    }
}
