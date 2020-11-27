<?php


namespace Plugin\SocialLogin4\Tests;


use Eccube\Tests\Web\AbstractWebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class PluginTestCase extends AbstractWebTestCase
{
    /**
     * @var ContainerInterface
     */
    protected $testContainer;

    public function setUp()
    {
        parent::setUp();

        $container = self::$kernel->getContainer();
        $this->testContainer = $container->has('test.service_container') ? $container->get('test.service_container') : $container;
    }
}
