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

1. [Implement `SettingsInterface`](https://github.com/groton-school/slim-skeleton/blob/cab11822b0beb536cf4a4d77aaab15f4624b391a/src/Application/Settings/SettingsInterface.php#L12-L17)
2. [Define `SettingsInterface` dependency](https://github.com/groton-school/slim-skeleton/blob/cab11822b0beb536cf4a4d77aaab15f4624b391a/app/dependencies.php#L37)
3. [Inject settings values](https://github.com/groton-school/slim-skeleton/blob/cab11822b0beb536cf4a4d77aaab15f4624b391a/src/Application/Settings/Settings.php#L27-L35)
4. Implement `ProviderRepositoryInterface` (or [use `SingleProvider`](https://github.com/groton-school/slim-skeleton/blob/cab11822b0beb536cf4a4d77aaab15f4624b391a/app/repositories.php#L14) and [inject `AbstractProvider` definition](https://github.com/groton-school/slim-skeleton/blob/cab11822b0beb536cf4a4d77aaab15f4624b391a/app/dependencies.php#L62-L70))
5. Store provider credentials securely (e.g. with `SingleProvider`, use [Google Cloud Secrets Manager](https://github.com/groton-school/slim-skeleton/blob/cab11822b0beb536cf4a4d77aaab15f4624b391a/app/dependencies.php#L65-L69))
6. [Define routes using `RouteBuilder::define()`](https://github.com/groton-school/slim-skeleton/blob/cab11822b0beb536cf4a4d77aaab15f4624b391a/app/routes.php#L25-L26)
7. [Implement a SPA client that uses those routes](https://github.com/groton-school/slim-skeleton/blob/cab11822b0beb536cf4a4d77aaab15f4624b391a/src/SPA/index.ts)

### groton-school/slim-skeleton@dev-gae/lti-tool-spa

[groton-school/slim-skeleton](https://github.com/groton-school/slim-skeleton/tree/gae/lti-tool-spa) is the canonical example of how this shim is meant to be used.
