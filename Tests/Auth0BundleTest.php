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

namespace Plugin\Auth0\Tests;

use Plugin\Auth0\Bundle\Auth0Bundle;
use Plugin\Auth0\DependencyInjection\Auth0Extension;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class Auth0BundleTest extends KernelTestCase
{
    public function testGetContainerExtension(): void
    {
        $bundle = new Auth0Bundle();
        $extension = $bundle->getContainerExtension();
        self::assertInstanceOf(Auth0Extension::class, $extension);
    }
}
