<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy;

use GrotonSchool\Slim\Norms\RouteBuilderInterface;
use GrotonSchool\Slim\OAuth2\APIProxy\Actions\AuthorizeAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Actions\DeauthorizeAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Actions\OwnerAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Actions\ProxyAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Actions\RedirectAction;
use Odan\Session\Middleware\SessionStartMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Interfaces\RouteGroupInterface;

class RouteBuilder implements RouteBuilderInterface
{
    public static function define(App $app, string $providerSlug = 'proxy'): RouteGroupInterface
    {
        $providerSlug = preg_replace('/^-?(.+)-?$/', '$1', preg_replace('/[^a-z0-9\-]+/', '-', $providerSlug));
        return $app->group("/$providerSlug", function (RouteCollectorProxyInterface $api) {
            $api->group("/login", function (RouteCollectorProxyInterface $login) {
                $login->get('/authorize', AuthorizeAction::class);
                $login->get('/redirect', RedirectAction::class);
                $login->get('/deauthorize', DeauthorizeAction::class);
            });
            $api->get('/owner', OwnerAction::class);
            $api->any('/proxy[/{path:.*}]', ProxyAction::class);
        })
            ->add(SessionStartMiddleware::class);
    }
}
