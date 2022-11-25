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

namespace Plugin\Auth0\Security\Authenticator;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Customer;
use Eccube\Entity\Master\CustomerStatus;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use KnpU\OAuth2ClientBundle\Security\Exception\FinishRegistrationException;
use Plugin\Auth0\Entity\Connection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class Auth0Authenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface
{
    /**
     * @var ClientRegistry
     */
    private $clientRegistry;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $entityManager,
        RouterInterface $router
    ) {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            $this->router->generate('auth0_connect'),
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'auth0_callback';
    }

    public function authenticate(Request $request)
    {
        $client = $this->clientRegistry->getClient('auth0');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $client) {
                $user = $client->fetchUserFromToken($accessToken);

                if (!$user->toArray()['email_verified']) {
                    throw new AuthenticationException();
                }

                /** @var Connection $Connection */
                $Connection = $this->entityManager->getRepository(Connection::class)
                    ->findOneBy(['user_id' => $user->toArray()['sub']]);

                // 連携済みの場合
                if ($Connection) {
                    $Customer = $Connection->getCustomer();
                    // 本会員の場合、会員情報を返す
                    if ($Customer->getStatus()->getId() === CustomerStatus::REGULAR) {
                        return $Customer;
                    } else {
                        throw new AuthenticationException();
                    }
                }

                /** @var Customer $Customer */
                $Customer = $this->entityManager->getRepository(Customer::class)
                    ->findOneBy(['email' => $user->getEmail()]);

                // 会員登録していない場合、会員登録ページへ
                if (null === $Customer) {
                    throw new FinishRegistrationException($user->toArray());
                }

                // 会員登録済みの場合はユーザー識別子を保存
                $Connection = new Connection();
                $Connection->setUserId($user->toArray()['sub']);
                $Connection->setCustomer($Customer->getId());
                $this->entityManager->persist($Connection);
                $this->entityManager->flush();

                return $Customer;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $targetUrl = $this->router->generate('mypage');

        return new RedirectResponse($targetUrl);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($exception instanceof FinishRegistrationException) {
            $this->saveUserInfoToSession($request, $exception);

            return new RedirectResponse($this->router->generate('entry'));
        } else {
            $this->saveAuthenticationErrorToSession($request, $exception);

            return new RedirectResponse($this->router->generate('mypage_login'));
        }
    }
}
