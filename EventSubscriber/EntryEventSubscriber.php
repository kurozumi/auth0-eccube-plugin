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


use Doctrine\ORM\EntityManagerInterface;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use KnpU\OAuth2ClientBundle\Security\Helper\FinishRegistrationBehavior;
use Plugin\SocialLogin4\Entity\Connection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EntryEventSubscriber implements EventSubscriberInterface
{
    use FinishRegistrationBehavior;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        SessionInterface $session,
        EntityManagerInterface $entityManager
    )
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
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
        $request = $args->getRequest();
        if(null === $request) {
            return;
        }

        $Customer = $args->getArgument('Customer');

        $userInfo = $this->getUserInfoFromSession($request);
        if($userInfo) {
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
