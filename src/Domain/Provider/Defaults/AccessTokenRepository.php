<?php

namespace GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\Defaults;

use GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken\AbstractAccessTokenRepository;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken\AccessTokenCookie;

trait AccessTokenRepository
{
    private ?AbstractAccessTokenRepository $repository = null;

    public function setAccessTokenRepostory(AbstractAccessTokenRepository $repository): void
    {
        $this->repository = $repository;
    }

    public function getAccessTokenRepository(): AbstractAccessTokenRepository
    {
        if (!$this->repository) {
            $this->repository = new AccessTokenCookie($this);
        }
        return $this->repository;
    }
}
