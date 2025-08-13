# groton-school/slim-spa-oauth2-client

Server-side actions and routes for authenticating to and accessing the an OAuth2 API from a Single Page (web) App

[![Latest Version](https://img.shields.io/packagist/v/groton-school/slim-spa-oauth2-client.svg)](https://packagist.org/packages/groton-school/slim-spa-oauth2-client)

## Install

```bash
composer require groton-school/slim-spa-oauth2-client
```

## Use

This is intended to be used in conjuction with a Single Page (web) App implemented using [@groton/canvas-api.client.spa](https://npmjs.org/package/@groton/canvas-api.client.spa) or similar in the context of a [slim-skeleton](https://github.com/slimphp/Slim-Skeleton#readme)-based app.

This package assumes that session-management is being handled by [odan/session](https://github.com/odan/session#readme).

1. Implement `SettingsInterface`
2. Define `SettingsInterface` dependency in `app/dependencies.php`
3. Inject settings values in `app/settings.php`
4. Implement `ProviderRepositoryInterface` (or use `SingleProvider`)
5. Define `ProviderRepositoryInterface` in `app/dependencies.php`
6. Inject provider values either in persistent storage or (if using `SingleProvider` in `app/settings`)
7. Define routes using `RouteBuilder::define()` in `app/routes.php`
