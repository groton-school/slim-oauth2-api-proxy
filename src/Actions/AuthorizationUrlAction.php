<?php

namespace GrotonSchool\Slim\SPA\OAuth2\Client\Actions;

use GrotonSchool\Slim\Norms\AbstractAction;
use League\OAuth2\Client\Provider\AbstractProvider;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class AuthorizationUrlAction extends AbstractAction
{
    public const STATE = self::class . '::session';

    public function __construct(
        private AbstractProvider $provider,
        private SessionInterface $session
    ) {
    }

    protected function invokeHook(
        ServerRequest $request,
        Response $response
    ): ResponseInterface {
        $this->session->set(self::STATE, $this->provider->getState());
        return $response->withRedirect($this->provider->getAuthorizationUrl());
    }
}
