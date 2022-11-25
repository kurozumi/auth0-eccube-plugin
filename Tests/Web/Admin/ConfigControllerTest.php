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

namespace Plugin\Auth0\Tests\Web\Admin;

use Eccube\Common\Constant;
use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;
use Symfony\Component\Filesystem\Filesystem;

class ConfigControllerTest extends AbstractAdminWebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $member = $this->createMember();
        $this->loginTo($member);
    }

    public function testAuth0情報を保存したらenvファイルに情報が追記されるか()
    {
        $envFile = static::getContainer()->getParameter('kernel.project_dir').'/.env';

        $fs = new Filesystem();
        $fs->copy($envFile, $envFile.'.backup');

        $this->client->request('POST', $this->generateUrl('social_login_admin_config'), [
            'config' => [
                'client_id' => 'dummy',
                'client_secret' => 'dummy',
                'custom_domain' => 'dummy',
                Constant::TOKEN_NAME => 'dummy',
            ],
        ]);

        $env = file_get_contents($envFile);

        $keys = [
            'OAUTH_AUTH0_CLIENT_ID',
            'OAUTH_AUTH0_CLIENT_SECRET',
            'OAUTH_AUTH0_CUSTOM_DOMAIN',
        ];

        foreach ($keys as $key) {
            $pattern = '/^('.$key.')=(.*)/m';
            if (preg_match($pattern, $env, $matches)) {
                self::assertEquals('dummy', $matches[2]);
            } else {
                self::fail(sprintf('%sが見つかりませんでした。', $key));
            }
        }

        // envファイルを戻す
        $fs->rename($envFile.'.backup', $envFile, true);
    }
}
