<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\Defaults;

trait HeaderInclude
{
    /**
     * @return string[]
     */
    public function getHeaderInclude(): array
    {
        return [
            'Accept',
            'Authorization',
            'Content-Type'
        ];
    }
}
