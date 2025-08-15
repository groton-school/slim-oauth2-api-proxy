<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Actions;

use Dflydev\FigCookies\FigResponseCookies;
use GrotonSchool\Slim\Norms\AbstractAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken\AccessTokenFactory;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use GuzzleHttp\Exception\GuzzleException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\Uri\Uri;
use Odan\Session\SessionInterface;
use Psr\Http\Message\RequestInterface;
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
     * @param RequestInterface $request 
     * @return string[][]
     */
    private function prepareHeaders(RequestInterface $request): array
    {
        $include = $this->provider->getHeaderInclude();
        $exclude = ['Authorization', ...$this->provider->getHeaderExclude()];
        $headers = [];

        foreach ($request->getHeaders() as $name => $values) {
            if (
                empty($include) ||
                !empty(array_filter($include, fn($pattern) => !!preg_match("/^$pattern/i", (string) $name)))
            ) {
                if (
                    empty($exclude) ||
                    empty(array_filter($exclude, fn($pattern) => !!preg_match("/^$pattern/i", (string)$name)))
                ) {
                    $headers[$name] = $values;
                }
            }
        }

        return $headers;
    }

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
                $token = $accessTokenFactory::merge(
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
                'authorize' => "/" . $this->provider->getSlug() . "/login/authorize"
            ])->withStatus(401);
        } else {
            try {
                $apiRequest = $this->provider->getAuthenticatedRequest(
                    $request->getMethod(),
                    (string) Uri::fromBaseUri($args['path'], $this->provider->getBaseApiUrl()),
                    $token,
                    [
                        'body' => $request->getBody(),
                        'headers' => $this->prepareHeaders($request),
                        'version' => $request->getProtocolVersion()
                    ]
                );
                $proxiedResponse = $this->provider->getResponse($apiRequest);
                $response = $response
                    ->withBody($proxiedResponse->getBody())
                    ->withStatus($proxiedResponse->getStatusCode());
            } catch (GuzzleException $e) {
                $parts = explode("\n", $e->getMessage());
                $response->getBody()->write(join("\n", array_slice($parts, 1)));
                $response = $response->withStatus($e->getCode(), $parts[0]);
            }
        }
        return FigResponseCookies::set(
            $response,
            $accessTokenFactory->toCookie($token)
        );
    }
}
