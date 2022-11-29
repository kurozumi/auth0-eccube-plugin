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

namespace Plugin\Auth0\EventSubscriber;

use Eccube\Request\Context;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AccessTokenSubscriber implements EventSubscriberInterface
{
    /**
     * @var Context
     */
    private $requestContext;

    /**
     * @var ClientRegistry
     */
    private $clientRegistry;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        Context          $requestContext,
        ClientRegistry   $clientRegistry,
        SessionInterface $session
    )
    {
        $this->requestContext = $requestContext;
        $this->clientRegistry = $clientRegistry;
        $this->session = $session;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (false === $event->isMainRequest()) {
            return;
        }

        if (false === $this->requestContext->isFront()) {
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
            KernelEvents::REQUEST => 'onKernelRequest'
        ];
    }
}
