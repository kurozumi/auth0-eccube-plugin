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

namespace Plugin\Auth0\EventSubscriber\Mypage;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Customer;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WithdrawSubscriber implements EventSubscriberInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            EccubeEvents::FRONT_MYPAGE_WITHDRAW_INDEX_COMPLETE => 'onFrontMyPageWithdrawIndexComplete',
        ];
    }

    public function onFrontMyPageWithdrawIndexComplete(EventArgs $args)
    {
        /** @var Customer $Customer */
        $Customer = $args->getArgument('Customer');

        foreach ($Customer->getConnections() as $connection) {
            $this->entityManager->remove($connection);
        }
        $this->entityManager->flush();

        // TODO:Auth0に登録されているアカウントを削除する処理が必要
    }
}
