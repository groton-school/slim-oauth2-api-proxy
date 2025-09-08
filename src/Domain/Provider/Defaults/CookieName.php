<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\Defaults;

trait CookieName
{
    public function getCookieName(): string
    {
        return  $this->getSlug() . '-tokens';
    }
}
