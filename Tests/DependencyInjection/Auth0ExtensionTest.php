<?php

namespace Plugin\Auth0\Tests\DependencyInjection;

use Plugin\Auth0\DependencyInjection\Auth0Extension;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Auth0ExtensionTest extends KernelTestCase
{
    public function testプラグインが無効化されていたらAuth0Authenticatorは追加されない()
    {
        $container = $this->createMock(ContainerBuilder::class);
        $container->expects($this->once())
            ->method('getParameter')
            ->willReturn([]);

        $extension = new Auth0Extension();
        $extension->prepend($container);
    }
}
