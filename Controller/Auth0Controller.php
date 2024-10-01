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

namespace Plugin\Auth0\Controller;

use Eccube\Controller\AbstractController;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Helper\FinishRegistrationBehavior;
use Plugin\Auth0\Repository\ConfigRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auth0")
 */
class Auth0Controller extends AbstractController
{
    use FinishRegistrationBehavior;

    /**
     * @param ClientRegistry $clientRegistry
     * @param ConfigRepository $configRepository
     *
     * @return RedirectResponse
     *
     * @throws \Exception
     *
     * @Route("/connect", name="auth0_connect")
     */
    public function connect(ClientRegistry $clientRegistry, ConfigRepository $configRepository): RedirectResponse
    {
        $Config = $configRepository->get();
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

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/connect/email_veridied", name="auth0_connect_email_verified")
     */
    public function emailVerified(Request $request): Response
    {
        $userInfo = $this->getUserInfoFromSession($request);
        if (!$userInfo) {
            throw new BadRequestHttpException();
        }

        return new Response(trans('plugin.social_login.front.email_verified'));
    }
}
