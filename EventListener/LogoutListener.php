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

use Auth0\SDK\Auth0;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

/**
 * Auth0でログインしている状態でログアウトした場合、Auth0からもログアウトするイベント
 */
class LogoutListener implements EventSubscriberInterface
{
    /**
     * @var Auth0
     */
    private $auth0;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(Auth0 $auth0, RouterInterface $router)
    {
        $this->auth0 = $auth0;
        $this->router = $router;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => ['onLogout'],
        ];
    }

    public function onLogout(LogoutEvent $event): void
    {
        if (null === $event->getResponse()) {
            return;
        }

        $logoutUrl = $this->auth0->logout($this->router->generate('homepage', [], UrlGeneratorInterface::ABSOLUTE_URL));
        $event->setResponse(new RedirectResponse($logoutUrl));
    }
}
