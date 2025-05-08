<?php

/*
 * This file is part of Auth0 for EC-CUBE
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 * https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Auth0\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Customer;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use KnpU\OAuth2ClientBundle\Security\Helper\FinishRegistrationBehavior;
use Plugin\Auth0\Entity\Connection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EntryListener implements EventSubscriberInterface
{
    use FinishRegistrationBehavior;

    /**
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->session = $requestStack->getSession();
        $this->entityManager = $entityManager;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EccubeEvents::FRONT_ENTRY_INDEX_COMPLETE => 'onFrontEntryIndexComplete',
        ];
    }

    /**
     * @param EventArgs $args
     *
     * @return void
     */
    public function onFrontEntryIndexComplete(EventArgs $args): void
    {
        $request = $args->getRequest();
        if (null === $request) {
            return;
        }

        /** @var Customer $Customer */
        $Customer = $args->getArgument('Customer');

        $userInfo = $this->getUserInfoFromSession($request);
        if ($userInfo) {
            $Connection = new Connection();
            $Connection->setUserId($userInfo['sub']);
            $Connection->setCustomer($Customer);
            $this->entityManager->persist($Connection);
            $this->entityManager->flush();

            // 会員登録完了時にAuth0のセッション削除
            $this->session->remove('guard.finish_registration.user_information');
        }
    }
}
