<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy;

interface SettingsInterface
{
    public function getOAuth2AuthenticatedRedirectUrl(): string;
    public function getOAuth2TokensCookieName(): string;
}
