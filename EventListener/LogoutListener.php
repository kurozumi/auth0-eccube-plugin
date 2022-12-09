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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListener implements EventSubscriberInterface
{
    /**
     * @var Auth0
     */
    private $auth0;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(Auth0 $auth0, SessionInterface $session)
    {
        $this->auth0 = $auth0;
        $this->session = $session;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => ['onLogout', 0],
        ];
    }

    public function onLogout(LogoutEvent $event): void
    {
        if (!$response = $event->getResponse()) {
            return;
        }

        // TODO: ログアウト時にAuth0側のログアウト処理の実装が必要
    }
}
