<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\SPA\OAuth2\Client\Actions;

use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\Modifier\SameSite;
use Dflydev\FigCookies\SetCookie;
use GrotonSchool\Slim\Norms\AbstractAction;
use GrotonSchool\Slim\SPA\OAuth2\Client\Domain\Provider\ProviderFactory;
use GrotonSchool\Slim\SPA\OAuth2\Client\SettingsInterface;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class RedirectAction extends AbstractAction
{
    public function __construct(
        private ProviderFactory $providerFactory,
        private SessionInterface $session,
        private SettingsInterface $settings
    ) {}

    protected function invokeHook(
        ServerRequest $request,
        Response $response,
        array $args = []
    ): ResponseInterface {
        $provider = $this->providerFactory->fromSession($this->session);
        $state = $request->getQueryParam('state');
        if (!$provider || empty($state) || $state !== $this->session->get(AuthorizeAction::STATE)) {
            error_log(json_encode([
                'provider' => $provider,
                'state' => $state,
                'session' => $this->session->all()
            ]));
            return $response->withStatus(401);
        }
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $request->getQueryParam('code')
        ]);
        return FigResponseCookies::set(
            $response->withRedirect(
                $this->settings->getOAuth2AuthenticatedRedirectUrl()
            ),
            SetCookie::createRememberedForever(
                $this->settings->getOAuth2TokensCookieName()
            )
                ->withValue(json_encode($token))
                ->withPath('/')
                ->withSameSite(SameSite::none())
                ->withSecure()
                ->withPartitioned()
        );
    }
}
