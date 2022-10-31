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

namespace Plugin\Auth0\Tests\Security\Authenticator;


use Eccube\Entity\Customer;
use Eccube\Tests\EccubeTestCase;
use KnpU\OAuth2ClientBundle\Security\Exception\FinishRegistrationException;
use Plugin\Auth0\Security\Authenticator\Auth0Authenticator;
use Plugin\Auth0\Security\Authenticator\OldAuth0Authenticator;
use Plugin\Auth0\Tests\PluginTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class Auth0AuthenticatorTest extends EccubeTestCase
{
    /**
     * @var OldAuth0Authenticator
     */
    protected $authenticator;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var SessionInterface
     */
    protected $session;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authenticator = new Auth0Authenticator(
            static::getContainer()->get('knpu.oauth2.registry'),
            static::getContainer()->get('doctrine.orm.default_entity_manager'),
            static::getContainer()->get('router')
        );

        $this->router = static::getContainer()->get('router');
        $this->session = static::getContainer()->get('session');

    }

    public function testStart()
    {
        $response = $this->authenticator->start(new Request());
        self::assertTrue($response->isRedirect($this->router->generate('auth0_connect')));
    }

    public function testCheckCredentials()
    {
        $customer = new Customer();
        self::assertEquals(true, $this->authenticator->checkCredentials('', $customer));
    }

    public function testSupportsRememberMe()
    {
        self::assertEquals(true, $this->authenticator->supportsRememberMe());
    }

    public function testOnAuthenticationFailure_FinishRegistrationException()
    {
        $request = new Request();
        $request->setSession($this->session);

        $response = $this->authenticator->onAuthenticationFailure($request, new FinishRegistrationException([]));
        self::assertTrue($response->isRedirect($this->router->generate('entry')));
    }

    public function testOnAuthenticationFailure_AuthenticationException()
    {
        $request = new Request();
        $request->setSession($this->session);

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

    public function testCreateAuthenticatedToken_customerToken()
    {
        $Customer = $this->createCustomer();
        $token = $this->authenticator->createAuthenticatedToken($Customer, 'customer');
        self::assertInstanceOf(UsernamePasswordToken::class, $token);
    }
}
