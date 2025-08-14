<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Actions;

use Dflydev\FigCookies\FigResponseCookies;
use GrotonSchool\Slim\Norms\AbstractAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken\AccessToken;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken\AccessTokenFactory;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\Uri\Uri;
use Odan\Session\SessionInterface;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Psr\Http\Message\ResponseInterface;
use UnexpectedValueException;

class ProxyAction extends AbstractAction
{
    public function __construct(
        private ProviderInterface $provider,
        private SessionInterface $session
    ) {}

    /**
     * @param ServerRequest $request 
     * @param Response $response 
     * @param array{path: string} $args 
     * @return ResponseInterface 
     */
    protected function invokeHook(
        ServerRequest $request,
        Response $response,
        array $args = []
    ): ResponseInterface {
        $accessTokenFactory = new AccessTokenFactory($this->provider);
        $token = $accessTokenFactory->fromRequest($request);
        if ($token && $token->hasExpired()) {
            try {
                $token = AccessToken::merge(
                    $token,
                    $this->provider->getAccessToken(
                        'refresh_token',
                        [
                            'refresh_token' => $token->getRefreshToken()
                        ]
                    )
                );
            } catch (IdentityProviderException $e) {
                $token = null;
                $response = $response
                    ->withBody($e->getResponseBody())
                    ->withStatus($e->getCode(), $e->getMessage());
            } catch (UnexpectedValueException $e) {
                $token = null;
                $response = $response
                    ->withJson(['error' => $e->getMessage()])
                    ->withStatus(500);
            }
        }
        if (!$token) {
            return  $response->withJson([
                'authorize' => Uri::fromBaseUri("/" . $this->provider->getSlug() . "/login/authorize", $request->getUri())
            ]);
        } else {
            $proxiedResponse = $this->provider->getResponse(
                $this->provider->getAuthenticatedRequest(
                    $request->getMethod(),
                    Uri::fromBaseUri($args['path'], $this->provider->getBaseApiUrl()),
                    $token,
                    [
                        'body' => $request->getBody(),
                        'headers' => $request->getHeaders(),
                        'version' => $request->getProtocolVersion()
                    ]
                )
            );
            $response = FigResponseCookies::set(
                $response->withBody(
                    $proxiedResponse->getBody()
                ),
                $accessTokenFactory->toCookie($token)
            )->withStatus($proxiedResponse->getStatusCode());
        }
        return $response;
    }
}
