<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\Defaults;

trait All
{
    use Slug,
        CookieName,
        AuthorizedRedirect,
        Headers;
}
