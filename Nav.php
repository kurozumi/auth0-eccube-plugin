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

namespace Plugin\Auth0;


use Eccube\Common\EccubeNav;

class Nav implements EccubeNav
{

    /**
     * @inheritDoc
     */
    public static function getNav()
    {
        // TODO: Implement getNav() method.
        return [
            'plugin_social_login' => [
                'name' => 'plguin.social_login.admin.config.title',
                'icon' => 'fa-users',
                'children' => [
                    'config' => [
                        'name' => 'plguin.social_login.admin.setting.title',
                        'url' => 'social_login_admin_config'
                    ]
                ]
            ]
        ];
    }
}
