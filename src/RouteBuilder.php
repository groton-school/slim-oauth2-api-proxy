<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy;

use GrotonSchool\Slim\Norms\RouteBuilderInterface;
use GrotonSchool\Slim\OAuth2\APIProxy\Actions\AuthorizeAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Actions\ProxyAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Actions\RedirectAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use Odan\Session\Middleware\SessionStartMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Interfaces\RouteGroupInterface;

class RouteBuilder implements RouteBuilderInterface
{
    public static function define(App $app, string $providerSlug = 'proxy'): RouteGroupInterface
    {
        $providerSlug = preg_replace('/^-?(.+)-?$/', '$1', preg_replace('/[^a-z0-9\-]+/', '-', $providerSlug));
        return $app->group("/$providerSlug", function (RouteCollectorProxyInterface $oauth2) {
            $oauth2->group("/login", function (RouteCollectorProxyInterface $login) {
                $login->get('/authorize', AuthorizeAction::class);
                $login->get('/redirect', RedirectAction::class);
            });
            $oauth2->any('/proxy/{path:.*}', ProxyAction::class);
        })
            ->add(SessionStartMiddleware::class);
    }

    public function __construct(private ProviderInterface $provider) {}

    public function defineProxyRoutes(App $app): RouteGroupInterface
    {
        return self::define($app, $this->provider->getSlug());
    }
}
