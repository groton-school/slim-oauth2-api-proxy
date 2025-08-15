<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Actions;

use GrotonSchool\Slim\Norms\AbstractAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken\AccessTokenFactory;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Psr\Http\Message\ResponseInterface;

class OwnerAction extends AbstractAction
{
    public function __construct(
        private ProviderInterface $provider
    ) {}

    protected function invokeHook(
        ServerRequest $request,
        Response $response,
        array $args = []
    ): ResponseInterface {
        $accessTokenFactory = new AccessTokenFactory($this->provider);
        $token = $accessTokenFactory->fromRequest($request);
        if ($token) {
            return $response->withJson($this->provider->getResourceOwner($token)->toArray());
        } else {
            return $response->withStatus(401);
        }
    }
}
