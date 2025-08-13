<?php

namespace GrotonSchool\Slim\SPA\OAuth2\Client\Domain\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;

interface ProviderRepositoryInterface
{
    public function find(string $host, string $clientId): ?AbstractProvider;
}
