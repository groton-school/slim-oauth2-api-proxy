<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider;

use League\OAuth2\Client\Grant\GrantFactory;
use League\OAuth2\Client\OptionProvider\OptionProviderInterface;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use League\OAuth2\Client\Tool\RequestFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface as HttpClientInterface;

/**
 * An interface that describes League\OAuth2\Client\Provider\AbstractProvider
 */
interface LeagueProviderInterface
{
    /**
     * Returns current guarded properties.
     *
     * @return array
     */
    public function getGuarded();

    /**
     * Determines if the given property is guarded.
     *
     * @param  string  $property
     * @return bool
     */
    public function isGuarded($property);

    /**
     * Sets the grant factory instance.
     *
     * @param  GrantFactory $factory
     * @return self
     */
    public function setGrantFactory(GrantFactory $factory);

    /**
     * Returns the current grant factory instance.
     *
     * @return GrantFactory
     */
    public function getGrantFactory();

    /**
     * Sets the request factory instance.
     *
     * @param  RequestFactory $factory
     * @return self
     */
    public function setRequestFactory(RequestFactory $factory);

    /**
     * Returns the request factory instance.
     *
     * @return RequestFactory
     */
    public function getRequestFactory();

    /**
     * Sets the HTTP client instance.
     *
     * @param  HttpClientInterface $client
     * @return self
     */
    public function setHttpClient(HttpClientInterface $client);

    /**
     * Returns the HTTP client instance.
     *
     * @return HttpClientInterface
     */
    public function getHttpClient();

    /**
     * Sets the option provider instance.
     *
     * @param  OptionProviderInterface $provider
     * @return self
     */
    public function setOptionProvider(OptionProviderInterface $provider);

    /**
     * Returns the option provider instance.
     *
     * @return OptionProviderInterface
     */
    public function getOptionProvider();
    /**
     * Returns the current value of the state parameter.
     *
     * This can be accessed by the redirect handler during authorization.
     *
     * @return string
     */
    public function getState();

    /**
     * Set the value of the pkceCode parameter.
     *
     * When using PKCE this should be set before requesting an access token.
     *
     * @param string $pkceCode
     * @return self
     */
    public function setPkceCode($pkceCode);

    /**
     * Returns the current value of the pkceCode parameter.
     *
     * This can be accessed by the redirect handler during authorization.
     *
     * @return string|null
     */
    public function getPkceCode();

    /**
     * Returns the base URL for authorizing a client.
     *
     * Eg. https://oauth.service.com/authorize
     *
     * @return string
     */
    public function getBaseAuthorizationUrl();

    /**
     * Returns the base URL for requesting an access token.
     *
     * Eg. https://oauth.service.com/token
     *
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params);

    /**
     * Returns the URL for requesting the resource owner's details.
     *
     * @param AccessToken $token
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token);

    /**
     * Builds the authorization URL.
     *
     * @param  array $options
     * @return string Authorization URL
     * @throws InvalidArgumentException
     */
    public function getAuthorizationUrl(array $options = []);

    /**
     * Redirects the client for authorization.
     *
     * @param  array $options
     * @param  callable|null $redirectHandler
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function authorize(
        array $options = [],
        ?callable $redirectHandler = null
    );

    /**
     * Requests an access token using a specified grant and option set.
     *
     * @param  mixed                $grant
     * @param  array<string, mixed> $options
     * @return AccessTokenInterface
     * @throws IdentityProviderException
     * @throws UnexpectedValueException
     * @throws GuzzleException
     */
    public function getAccessToken($grant, array $options = []);

    /**
     * Returns a PSR-7 request instance that is not authenticated.
     *
     * @param  string $method
     * @param  string $url
     * @param  array $options
     * @return RequestInterface
     */
    public function getRequest($method, $url, array $options = []);

    /**
     * Returns an authenticated PSR-7 request instance.
     *
     * @param  string $method
     * @param  string $url
     * @param  AccessTokenInterface|string|null $token
     * @param  array $options Any of "headers", "body", and "protocolVersion".
     * @return RequestInterface
     */
    public function getAuthenticatedRequest($method, $url, $token, array $options = []);


    /**
     * Sends a request instance and returns a response instance.
     *
     * WARNING: This method does not attempt to catch exceptions caused by HTTP
     * errors! It is recommended to wrap this method in a try/catch block.
     *
     * @param  RequestInterface $request
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function getResponse(RequestInterface $request);

    /**
     * Sends a request and returns the parsed response.
     *
     * @param  RequestInterface $request
     * @return mixed
     * @throws IdentityProviderException
     * @throws UnexpectedValueException
     * @throws GuzzleException
     */
    public function getParsedResponse(RequestInterface $request);

    /**
     * Requests and returns the resource owner of given access token.
     *
     * @param  AccessToken $token
     * @return ResourceOwnerInterface
     * @throws IdentityProviderException
     * @throws UnexpectedValueException
     * @throws GuzzleException
     */
    public function getResourceOwner(AccessToken $token);

    /**
     * Returns all headers used by this provider for a request.
     *
     * The request will be authenticated if an access token is provided.
     *
     * @param  mixed|null $token object or string
     * @return array
     */
    public function getHeaders($token = null);
}
