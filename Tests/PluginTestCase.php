<?php


namespace Plugin\SocialLogin4\Tests;


use Eccube\Tests\Web\AbstractWebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class PluginTestCase extends AbstractWebTestCase
{
    /**
     * @var ContainerInterface
     */
    protected static $container;

    public function setUp()
    {
        parent::setUp();

        $container = self::$kernel->getContainer();
        static::$container = $container->has('test.service_container') ? $container->get('test.service_container') : $container;
    }
}
