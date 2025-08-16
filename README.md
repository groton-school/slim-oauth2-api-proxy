# groton-school/slim-oauth2-api-proxy

Server-side actions and routes for authenticating to and accessing an REST API from a web client

[![Latest Version](https://img.shields.io/packagist/v/groton-school/slim-oauth2-api-proxy.svg)](https://packagist.org/packages/groton-school/slim-oauth2-api-proxy)

## Install

```bash
composer require groton-school/oauth2-api-proxy
```

## Use

Due to [CORS](https://developer.mozilla.org/en-US/docs/Web/HTTP/Guides/CORS) restrictions, a web app can't directly access an arbitrary REST API. This package provides a server-side proxy for the web client to use to access the REST API, storing the the user's API access tokens on the client side as web cookies.

1. Implement `ProviderInterface`. This is intended to be done with one of the [`League/oauth2-client`](https://oauth2-client.thephpleague.com) implementations. See [groton-school/slim-canvas-api-proxy](https://github.com/groton-school/slim-canvas-api-proxy#readme) for a concrete example (which makes use of the `Defaults` traits provided for convenience).
2. [Inject the implementation as a dependency.](https://github.com/groton-school/slim-skeleton/blob/0810344ec844912300e3834984d6a16893cde921/app/dependencies.php#L60-L68) (Of course, make sure that you store your API credentials somewhere secure!)
3. [use `RouteBuilder` to define the necessary routes.](https://github.com/groton-school/slim-skeleton/blob/0810344ec844912300e3834984d6a16893cde921/app/routes.php#L23-L24)
4. Access the client from a web app. A concrete example of this is [@groton/canvas-api.client.web](https://npmjs.com/package/@groton/canvas-api.client.web).

### groton-school/slim-skeleton@dev-gae/lti-tool_canvas-api-proxy

[groton-school/slim-skeleton](https://github.com/groton-school/slim-skeleton/tree/gae/lti-tool_canvas-api-proxy) is the canonical example of how this shim is meant to be used.
