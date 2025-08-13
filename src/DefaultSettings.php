<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\SPA\OAuth2\Client;

class DefaultSettings implements SettingsInterface
{
    public function getOAuth2AuthenticatedRedirectUrl(): string
    {
        return '/';
    }

    public function getOAuth2TokensCookieName(): string
    {
        return 'tokens';
    }
}
