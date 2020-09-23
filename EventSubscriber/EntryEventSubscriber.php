<?php
/**
 * This file is part of SocialLogin4
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 *  https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\SocialLogin4\EventSubscriber;


use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EntryEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return [
            EccubeEvents::FRONT_ENTRY_INDEX_COMPLETE => 'onFrontEntryIndexComplete'
        ];
    }

    public function onFrontEntryIndexComplete(EventArgs $args)
    {
        // 会員登録完了時にAuth0のセッション削除
        $this->session->remove('guard.finish_registration.user_information');
    }
}
