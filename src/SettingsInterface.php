<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\SPA\OAuth2\Client;

interface SettingsInterface
{
    public function getOAuth2AuthenticatedRedirectUrl(): string;
    public function getOAuth2TokensCookieName(): string;
}
