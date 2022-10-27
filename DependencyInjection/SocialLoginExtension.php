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

namespace Plugin\Auth0\DependencyInjection;


use Eccube\DependencyInjection\EccubeExtension;
use Plugin\Auth0\Security\Authenticator\Auth0Authenticator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SocialLoginExtension extends EccubeExtension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
    }

    /**
     * @inheritDoc
     */
    public function prepend(ContainerBuilder $container)
    {
        $plugins = $container->getParameter('eccube.plugins.enabled');

        if(!in_array('Auth0', $plugins)) {
            return;
        }

        // セキュリティ設定にAuth0Authenticator追加
        $extensionConfigsRefl = new \ReflectionProperty(ContainerBuilder::class, 'extensionConfigs');
        $extensionConfigsRefl->setAccessible(true);
        $extensionConfigs = $extensionConfigsRefl->getValue($container);

        foreach($extensionConfigs["security"] as $key => $security) {
            if(isset($security["firewalls"])) {
                $extensionConfigs["security"][$key]["firewalls"]["customer"]["entry_point"] = Auth0Authenticator::class;
                $extensionConfigs["security"][$key]["firewalls"]["customer"]["guard"]["authenticators"][] = Auth0Authenticator::class;
            }
        }

        $extensionConfigsRefl->setValue($container, $extensionConfigs);
    }
}
