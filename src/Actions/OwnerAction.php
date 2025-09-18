<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Actions;

use GrotonSchool\Slim\Norms\AbstractAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
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
        try {
            $token = $this->provider->getAccessTokenRepository()->getToken($request);
        } catch (IdentityProviderException $e) {
            return $response->withStatus(
                $e->getCode(),
                $e->getMessage()
            );
        }
        if ($token) {
            return $this->provider
                ->getAccessTokenRepository()
                ->setToken(
                    $token,
                    $request,
                    $response->withJson($this->provider->getResourceOwner($token)->toArray())
                );
        } else {
            return $response->withStatus(401);
        }
    }
}
