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

namespace Plugin\Auth0\Controller;

use Eccube\Controller\AbstractController;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Plugin\Auth0\Repository\ConfigRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auth0")
 */
class Auth0Controller extends AbstractController
{
    /**
     * @param ClientRegistry $clientRegistry
     * @param ConfigRepository $configRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/connect", name="auth0_connect")
     */
    public function connect(ClientRegistry $clientRegistry, ConfigRepository $configRepository)
    {
        $Config = $configRepository->get();
        if (!$Config) {
            throw new NotFoundHttpException();
        }

        if (!$Config->getClientId() || !$Config->getClientSecret() || !$Config->getCustomDomain()) {
            throw new NotFoundHttpException();
        }

        return $clientRegistry
            ->getClient('auth0')
            ->redirect(['openid email email_verified profile']);
    }

    /**
     * @return void
     *
     * @Route("/connect/callback", name="auth0_connect_callback")
     */
    public function callback()
    {
    }
}
