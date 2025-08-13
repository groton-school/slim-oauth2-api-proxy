<?php

namespace GrotonSchool\Slim\SPA\OAuth2\Client\Domain\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use Odan\Session\SessionInterface;
use Slim\Http\ServerRequest;

class ProviderFactory
{
    private const HOST = self::class . '::host';
    private const CLIENT_ID = self::class . '::client_id';

    public function __construct(private ProviderRepositoryInterface $providers) {}

    public function fromRequest(ServerRequest $request): ?AbstractProvider
    {
        return $this->providers->find(
            $request->getQueryParam('host', ''),
            $request->getQueryParam('client_id', '')
        );
    }

    public function toSession(AbstractProvider $provider, SessionInterface $session): void
    {
        $session->set(self::HOST, parse_url($provider->getBaseAuthorizationUrl(), PHP_URL_HOST));
        $options = [];
        parse_str($provider->getAuthorizationUrl(), $options);
        $session->set(self::CLIENT_ID, $options['client_id']);
    }

    public function fromSession(SessionInterface $session): ?AbstractProvider
    {
        return $this->providers->find(
            $session->get(self::HOST),
            $session->get(self::CLIENT_ID)
        );
    }
}
