<?php

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider;

interface ProviderRepositoryInterface
{
    public function find(string $host, string $clientId): ?ProviderInterface;
}
