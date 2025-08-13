<?php

namespace GrotonSchool\Slim\SPA\OAuth2\Client\Actions;

use GrotonSchool\Slim\Norms\AbstractAction;
use GrotonSchool\Slim\SPA\OAuth2\Client\Domain\Provider\ProviderFactory;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class AuthorizeAction extends AbstractAction
{
    public const STATE = self::class . '::session';

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
            $this->session->set(self::STATE, $provider->getState());
            $this->providerFactory->toSession($provider, $this->session);
            return $response->withRedirect($provider->getAuthorizationUrl());
        } else {
            return $response->withStatus(400, "OAuth 2.0 host and/or client_id not specified");
        }
    }
}
