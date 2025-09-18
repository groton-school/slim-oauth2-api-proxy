<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Actions;

use Dflydev\FigCookies\FigResponseCookies;
use GrotonSchool\Slim\Norms\AbstractAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class RedirectAction extends AbstractAction
{
    public function __construct(
        private ProviderInterface $provider,
        private SessionInterface $session,
    ) {}

    protected function invokeHook(
        ServerRequest $request,
        Response $response,
        array $args = []
    ): ResponseInterface {
        $state = $request->getQueryParam('state');
        if (empty($state) || $state !== $this->session->get(AuthorizeAction::STATE)) {
            return $response->withStatus(400);
        }
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $request->getQueryParam('code')
        ]);
        return $this->provider
            ->getAccessTokenRepository()
            ->setToken($token, $request, $response->withRedirect(
                $this->provider->getAuthorizedRedirect()
            ));
    }
}
