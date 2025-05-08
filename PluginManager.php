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

namespace Plugin\Auth0;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Plugin\AbstractPluginManager;
use Plugin\Auth0\Entity\Config;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class PluginManager extends AbstractPluginManager
{
    /**
     * @param array $meta
     * @param ContainerInterface $container
     *
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function enable(array $meta, ContainerInterface $container): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.orm.entity_manager');

        $Config = $entityManager->getRepository(Config::class)->find(Config::ID);
        if (!$Config) {
            $Config = new Config();
            $entityManager->persist($Config);
            $entityManager->flush();
        }
    }

    public function update(array $meta, ContainerInterface $container): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.orm.entity_manager');

        $Config = $entityManager->getRepository(Config::class)->find(Config::ID);
        if (!$Config) {
            $Config = new Config();
            $entityManager->persist($Config);
            $entityManager->flush();
        }
    }
}
