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
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Security\Exception\FinishRegistrationException;
use Plugin\Auth0\Entity\Connection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class Auth0Authenticator extends SocialAuthenticator
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
        ClientRegistry         $clientRegistry,
        EntityManagerInterface $entityManager,
        RouterInterface        $router
    )
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'auth0_callback';
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            $this->router->generate("auth0_connect"),
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getAuth0Client());
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user = $this->getAuth0Client()
            ->fetchUserFromToken($credentials);

        if (!$user->toArray()['email_verified']) {
            throw new AuthenticationException();
        }

        /** @var Connection $Connection */
        $Connection = $this->entityManager->getRepository(Connection::class)
            ->findOneBy(['user_id' => $user->toArray()["sub"]]);

        // 連携済みの場合
        if ($Connection) {
            return $Connection->getCustomer();
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
        $Connection->setUserId($user->toArray()["sub"]);
        $Connection->setCustomerId($Customer->getId());
        $this->entityManager->persist($Connection);
        $this->entityManager->flush();

        return $Customer;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // 会員登録していない場合
        if ($exception instanceof FinishRegistrationException) {
            $this->saveUserInfoToSession($request, $exception);
            return new RedirectResponse($this->router->generate('entry'));
        } else {
            $this->saveAuthenticationErrorToSession($request, $exception);
            return new RedirectResponse($this->router->generate('mypage_login'));
        }
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetUrl = $this->router->generate('mypage');

        return new RedirectResponse($targetUrl);
    }

    /**
     * @return OAuth2ClientInterface
     */
    private function getAuth0Client(): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient('auth0');
    }

    /**
     * EC-CUBEがUsernamePasswordTokenなので合わせる
     *
     * @param UserInterface $user
     * @param string $providerKey
     * @return UsernamePasswordToken|\Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken
     */
    public function createAuthenticatedToken(UserInterface $user, $providerKey)
    {
        if ($user instanceof Customer && $providerKey === 'customer') {
            return new UsernamePasswordToken($user, null, $providerKey, ['ROLE_USER']);
        }

        return parent::createAuthenticatedToken($user, $providerKey);
    }
}
