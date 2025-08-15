<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\Defaults;

trait AuthorizedRedirect
{
    public function getAuthorizedRedirect(): string
    {
        return '/';
    }
}
