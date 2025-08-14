<?php

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;

class SingleProvider implements ProviderRepositoryInterface
{
    public function __construct(private AbstractProvider $provider) {}

    public function find(string $url = '', string $clientId = ''): ?AbstractProvider
    {
        $options = [];
        parse_str($this->provider->getAuthorizationUrl(), $options);
        if (
            ($url == '' ||
                $url == parse_url(
                    $this->provider->getBaseAuthorizationUrl(),
                    PHP_URL_HOST
                )
            ) && (
                $clientId == '' ||
                $clientId === $options['client_id']
            )
        ) {
            return $this->provider;
        }
        return null;
    }
}
