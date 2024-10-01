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

namespace Plugin\Auth0\Tests\Security\Authenticator;

use Eccube\Tests\EccubeTestCase;
use KnpU\OAuth2ClientBundle\Security\Exception\FinishRegistrationException;
use Plugin\Auth0\Security\Authenticator\Auth0Authenticator;
use Plugin\Auth0\Security\Exception\EmailVerifiedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class Auth0AuthenticatorTest extends EccubeTestCase
{
    /**
     * @var Auth0Authenticator
     */
    protected $authenticator;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authenticator = new Auth0Authenticator(
            static::getContainer()->get('knpu.oauth2.registry'),
            static::getContainer()->get('doctrine.orm.default_entity_manager'),
            static::getContainer()->get('router'),
            static::getContainer()->get('request_stack')
        );

        $this->router = static::getContainer()->get('router');
        $this->requestStack = static::getContainer()->get('request_stack');
    }

    public function testStart()
    {
        $response = $this->authenticator->start(new Request());
        self::assertTrue($response->isRedirect($this->router->generate('auth0_connect')));
    }

    public function testOnAuthenticationFailureEmailVerifiedException()
    {
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));

        $response = $this->authenticator->onAuthenticationFailure($request, new EmailVerifiedException([]));
        self::assertTrue($response->isRedirect($this->router->generate('auth0_connect_email_verified')));
    }

    public function testOnAuthenticationFailureFinishRegistrationException()
    {
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));

        $response = $this->authenticator->onAuthenticationFailure($request, new FinishRegistrationException([]));
        self::assertTrue($response->isRedirect($this->router->generate('entry')));
    }

    public function testOnAuthenticationFailureAuthenticationException()
    {
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));

        $response = $this->authenticator->onAuthenticationFailure($request, new AuthenticationException());
        self::assertTrue($response->isRedirect($this->router->generate('mypage_login')));
    }

    public function testOnAuthenticationSuccess()
    {
        $Customer = $this->createCustomer();
        $token = new UsernamePasswordToken($Customer, 'customer', ['ROLE_USER']);
        $response = $this->authenticator->onAuthenticationSuccess(new Request(), $token, 'customer');
        self::assertTrue($response->isRedirect($this->router->generate('mypage')));
    }
}
