<?php

namespace GrotonSchool\Slim\SPA\OAuth2\Client\Domain\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;

class SingleProvider implements ProviderRepositoryInterface
{
    public function __construct(private AbstractProvider $provider) {}

    public function find(string $url = '', string $clientId = ''): ?AbstractProvider
    {
        $options = [];
        parse_str($this->provider->getAuthorizationUrl(), $options);
        error_log(json_encode([
            'url' => $url,
            'clientId' => $clientId,
            'url == ""' => $url == '',
            'clientId == ""' => $clientId == ''
        ]));
        if (
            ($url == '' ||
                parse_url(
                    $url,
                    PHP_URL_HOST
                ) === parse_url(
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
