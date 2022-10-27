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

namespace Plugin\Auth0\Tests\Web;


use Eccube\Tests\Web\AbstractWebTestCase;

class Auth0ControllerTest extends AbstractWebTestCase
{
    public function testAuth0の設定をしていなかったらNotFound()
    {
        $this->client->request('GET', $this->generateUrl('auth0_connect'));
        self::assertTrue($this->client->getResponse()->isNotFound());
    }

    public function testAuth0の設定をしていたらリダイレクト()
    {
        self::markTestIncomplete();
    }
}
