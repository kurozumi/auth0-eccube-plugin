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

        $this->client->request('GET', $this->generateUrl('auth0_connect'));
        self::assertTrue($this->client->getResponse()->isRedirect());
    }
}
