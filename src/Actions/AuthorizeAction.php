<?php

namespace GrotonSchool\Slim\OAuth2\APIProxy\Actions;

use GrotonSchool\Slim\Norms\AbstractAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class AuthorizeAction extends AbstractAction
{
    public const STATE = self::class . '::state';

    public function __construct(
        private ProviderInterface $provider,
        private SessionInterface $session
    ) {}

    protected function invokeHook(
        ServerRequest $request,
        Response $response,
        array $args = []
    ): ResponseInterface {
        $authUrl = $this->provider->getAuthorizationUrl();
        $this->session->set(self::STATE, $this->provider->getState());
        return $response->withRedirect($authUrl);
    }
}
