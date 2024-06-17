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

namespace Plugin\Auth0\DependencyInjection;

use Plugin\Auth0\Security\Authenticator\Auth0Authenticator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class Auth0Extension extends Extension implements PrependExtensionInterface
{
    public const PLUGIN_NAME = 'Auth0';

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function prepend(ContainerBuilder $container): void
    {
        $enabledPlugins = $container->getParameter('eccube.plugins.enabled');
        if (!in_array(static::PLUGIN_NAME, $enabledPlugins, true)) {
            return;
        }

        // セキュリティ設定にAuth0Authenticator追加
        $extensionConfigsRefl = new \ReflectionProperty(ContainerBuilder::class, 'extensionConfigs');
        $extensionConfigsRefl->setAccessible(true);
        $extensionConfigs = $extensionConfigsRefl->getValue($container);

        foreach ($extensionConfigs['security'] as $key => $security) {
            if (isset($security['firewalls'])) {
                $extensionConfigs['security'][$key]['firewalls']['customer']['entry_point'] = Auth0Authenticator::class;
                $extensionConfigs['security'][$key]['firewalls']['customer']['custom_authenticators'][] = Auth0Authenticator::class;
            }
        }

        $extensionConfigsRefl->setValue($container, $extensionConfigs);
    }
}
