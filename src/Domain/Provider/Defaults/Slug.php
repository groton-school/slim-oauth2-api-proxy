<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\Defaults;

trait Slug
{
    public function getSlug(): string
    {
        return 'api';
    }
}
