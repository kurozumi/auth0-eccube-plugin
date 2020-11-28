<?php


namespace Plugin\SocialLogin4\Tests;


use Eccube\Tests\Web\AbstractWebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class PluginTestCase extends AbstractWebTestCase
{
    /**
     * @var ContainerInterface
     */
    protected static $testContainer;

    public function setUp()
    {
        parent::setUp();

        $container = self::$kernel->getContainer();
        static::$testContainer = $container->has('test.service_container') ? $container->get('test.service_container') : $container;
    }
}
