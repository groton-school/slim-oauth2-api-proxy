<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy;

use GrotonSchool\Slim\Norms\RouteBuilderInterface;
use GrotonSchool\Slim\OAuth2\APIProxy\Actions\AuthorizeAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Actions\DeauthorizeAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Actions\OwnerAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Actions\ProxyAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Actions\RedirectAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use Odan\Session\Middleware\SessionStartMiddleware;
use Odan\Session\SessionInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Interfaces\RouteGroupInterface;

class RouteBuilder implements RouteBuilderInterface
{
    public function __construct(
        private ProviderInterface $provider,
        private SessionInterface $session
    ) {}

    public function define(App $app, MiddlewareInterface|string|callable ...$innerMiddleware): RouteGroupInterface
    {
        $providerSlug = preg_replace(
            '/^-?(.+)-?$/',
            '$1',
            preg_replace(
                '/[^a-z0-9\-]+/',
                '-',
                $this->provider->getSlug()
            )
        );
        $provider = $this->provider;
        $session = $this->session;
        $group = $app->group("/$providerSlug", function (RouteCollectorProxyInterface $api) use ($provider, $session) {
            $api->group("/login", function (RouteCollectorProxyInterface $login) use ($provider, $session) {
                $login->get('/authorize', new AuthorizeAction(
                    $provider,
                    $session
                ));
                $login->get('/redirect', new RedirectAction(
                    $provider,
                    $session
                ));
                $login->get('/deauthorize', new DeauthorizeAction($provider));
            });
            $api->get('/owner', new OwnerAction($provider));
            $api->any('/proxy[/{path:.*}]', new ProxyAction(
                $provider,
                $session
            ));
        });

        foreach ($innerMiddleware as $middleware) {
            $group = $group->add($middleware);
        }

        return $group->add(SessionStartMiddleware::class);
    }
}
