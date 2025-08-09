<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\SPA\OAuth2\Client;

use GrotonSchool\Slim\Norms\RouteBuilderInterface;
use GrotonSchool\Slim\SPA\OAuth2\Client\Actions\AuthorizeAction;
use GrotonSchool\Slim\SPA\OAuth2\Client\Actions\RedirectAction;
use GrotonSchool\Slim\SPA\OAuth2\Client\Actions\RefreshTokenAction;
use Odan\Session\Middleware\SessionStartMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Interfaces\RouteGroupInterface;

class RouteBuilder implements RouteBuilderInterface
{
    public static function define(App $app, string $providerSlug = 'oauth2'): RouteGroupInterface
    {
        $providerSlug = preg_replace("/[^a-z0-9\-]+/", '-', $providerSlug);
        return $app->group("/login/$providerSlug", function (RouteCollectorProxyInterface $login) {
            $login->get('/authorize/{clientId}', AuthorizeAction::class);
            $login->get('/redirect', RedirectAction::class);
            $login->get('/refresh', RefreshTokenAction::class);
        })
            ->add(SessionStartMiddleware::class);
    }
}
