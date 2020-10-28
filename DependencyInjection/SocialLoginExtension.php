<?php
/**
 * This file is part of SocialLogin4
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 *  https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\SocialLogin4\DependencyInjection;


use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Configuration as DoctrineBundleConfiguration;
use Doctrine\DBAL\DriverManager;
use Eccube\DependencyInjection\EccubeExtension;
use Plugin\SocialLogin4\Security\Authenticator\Auth0Authenticator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SocialLoginExtension extends EccubeExtension
{
    const PLUGIN_NAME = "SocialLogin4";

    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // TODO: Implement load() method.
    }

    /**
     * @inheritDoc
     */
    public function prepend(ContainerBuilder $container)
    {
        $enabledPlugins = $this->getEnabledPlugins($container);

        // 無効化しても処理が実行されるので無効の場合は処理を停止
        if (!in_array(self::PLUGIN_NAME, $enabledPlugins)) {
            return;
        }

        // セキュリティ設定にAuth0Authenticator追加
        $extensionConfigsRefl = new \ReflectionProperty(ContainerBuilder::class, 'extensionConfigs');
        $extensionConfigsRefl->setAccessible(true);
        $extensionConfigs = $extensionConfigsRefl->getValue($container);

        foreach($extensionConfigs["security"] as $key => $security) {
            if(isset($security["firewalls"])) {
                $extensionConfigs["security"][$key]["firewalls"]["customer"]["guard"]["authenticators"][] = Auth0Authenticator::class;
            }
        }

        $extensionConfigsRefl->setValue($container, $extensionConfigs);
    }

    protected function getEnabledPlugins(ContainerBuilder $container)
    {
        // doctrine.yml, または他のprependで差し込まれたdoctrineの設定値を取得する.
        $configs = $container->getExtensionConfig('doctrine');

        // $configsは, env変数(%env(xxx)%)やパラメータ変数(%xxx.xxx%)がまだ解決されていないため, resolveEnvPlaceholders()で解決する
        // @see https://github.com/symfony/symfony/issues/22456
        $configs = $container->resolveEnvPlaceholders($configs, true);

        // doctrine bundleのconfigurationで設定値を正規化する.
        $configuration = new DoctrineBundleConfiguration($container->getParameter('kernel.debug'));
        $config = $this->processConfiguration($configuration, $configs);

        // prependのタイミングではコンテナのインスタンスは利用できない.
        // 直接dbalのconnectionを生成し, dbアクセスを行う.
        $params = $config['dbal']['connections'][$config['dbal']['default_connection']];
        // ContainerInterface::resolveEnvPlaceholders() で取得した DATABASE_URL は
        // % がエスケープされているため、環境変数から取得し直す
        $params['url'] = env('DATABASE_URL');
        $conn = DriverManager::getConnection($params);

        if (!$this->isConnected($conn)) {
            return;
        }

        $stmt = $conn->query('select * from dtb_plugin');
        $plugins = $stmt->fetchAll();

        $enabled = [];
        foreach ($plugins as $plugin) {
            if (array_key_exists('enabled', $plugin) && $plugin['enabled']) {
                $enabled[] = $plugin['code'];
            }
        }

        return $enabled;
    }
}
