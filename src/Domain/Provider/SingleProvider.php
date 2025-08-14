<?php

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider;

class SingleProvider implements ProviderRepositoryInterface
{
    public function __construct(private ProviderInterface $provider) {}

    public function find(string $url = '', string $clientId = ''): ?ProviderInterface
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
