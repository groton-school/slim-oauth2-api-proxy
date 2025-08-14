<?php

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;

interface ProviderRepositoryInterface
{
    public function find(string $host, string $clientId): ?AbstractProvider;
}
