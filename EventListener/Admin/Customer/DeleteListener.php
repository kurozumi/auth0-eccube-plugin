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

namespace Plugin\Auth0\EventListener\Admin\Customer;

use Auth0\SDK\Auth0;
use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Customer;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Plugin\Auth0\Entity\Connection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DeleteListener implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Auth0
     */
    private $auth0;

    public function __construct(EntityManagerInterface $entityManager, Auth0 $auth0)
    {
        $this->entityManager = $entityManager;
        $this->auth0 = $auth0;
    }

    public static function getSubscribedEvents()
    {
        return [
            EccubeEvents::ADMIN_CUSTOMER_DELETE_COMPLETE => 'onAdminCustomerDeleteComplete',
        ];
    }

    public function onAdminCustomerDeleteComplete(EventArgs $args)
    {
        /** @var Customer $Customer */
        $Customer = $args->getArgument('Customer');

        /** @var Connection $connection */
        foreach ($Customer->getConnections() as $connection) {
            $this->entityManager->remove($connection);
            $this->auth0->management()->users()->delete($connection->getUserId());
        }

        $this->entityManager->flush();
    }
}
