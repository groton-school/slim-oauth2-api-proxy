<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider;

use GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken\AbstractAccessTokenRepository;

interface ProviderInterface extends LeagueProviderInterface
{
    public function getSlug(): string;
    public function getAuthorizedRedirect(): string;
    public function getBaseApiUrl(): string;

    /**
     * @return string[]
     */
    public function getHeaderInclude(): array;

    /**
     * @return string[]
     */
    public function getHeaderExclude(): array;

    public function setAccessTokenRepostory(AbstractAccessTokenRepository $repository): void;

    public function getAccessTokenRepository(): AbstractAccessTokenRepository;
}
