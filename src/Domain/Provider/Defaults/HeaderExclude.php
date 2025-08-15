<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\Defaults;

trait HeaderExclude
{
    /**
     * @return string[]
     */
    public function getHeaderExclude(): array
    {
        return [
            'Cookie',
            'Referer',
            'X-AppEngine'
        ];
    }
}
