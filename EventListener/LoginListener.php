<?php

/**
 * This file is part of Auth0
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 *  https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Auth0\EventListener;

use Eccube\Entity\Customer;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginListener implements EventSubscriberInterface
{
    /**
     * @var ClientRegistry
     */
    private $clientRegistry;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        ClientRegistry   $clientRegistry,
        SessionInterface $session
    )
    {
        $this->clientRegistry = $clientRegistry;
        $this->session = $session;
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $token = $event->getAuthenticatedToken();
        if (!$token instanceof Customer) {
            return;
        }

        /** @var AccessToken $accessToken */
        $accessToken = $this->session->get('access_token');
        if ($accessToken instanceof AccessToken && $accessToken->hasExpired()) {
            $client = $this->clientRegistry->getClient('auth0');
            $accessToken = $client->refreshAccessToken($accessToken->getRefreshToken());

            $this->session->set('access_token', $accessToken);
        }
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }
}
