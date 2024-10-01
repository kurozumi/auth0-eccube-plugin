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

namespace Plugin\Auth0\Tests\Web;

use Eccube\Tests\Web\AbstractWebTestCase;
use Plugin\Auth0\Entity\Config;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Auth0ControllerTest extends AbstractWebTestCase
{
    public function testAuth0の設定をしていなかったらNotFound()
    {
        /** @var Config $config */
        $config = $this->entityManager->getRepository(Config::class)->find(Config::ID);
        $config
            ->setClientId(null)
            ->setClientSecret(null)
            ->setCustomDomain(null);

        $this->client->request('GET', $this->generateUrl('auth0_connect'));
        self::assertTrue($this->client->getResponse()->isNotFound());
    }

    public function testAuth0の設定をしていたらリダイレクト()
    {
        /** @var Config $config */
        $config = $this->entityManager->getRepository(Config::class)->find(Config::ID);
        $config
            ->setClientId('aaa')
            ->setClientSecret('aaa')
            ->setCustomDomain('aaa');

        putenv('OAUTH_AUTH0_CLIENT_ID=aaa');
        putenv('OAUTH_AUTH0_CLIENT_SECRET=aaa');
        putenv('OAUTH_AUTH0_CUSTOM_DOMAIN=aaa');
        putenv('OAUTH_AUTH0_COOKIE_SECRET=aaa');

        $this->client->request('GET', $this->generateUrl('auth0_connect'));
        self::assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testUserInfoがない場合メールアドレス認証案内ページにアクセスできない()
    {
        $this->client->request('GET', $this->generateUrl('auth0_connect_email_verified'));
        self::expectException(BadRequestHttpException::class);
    }
}
