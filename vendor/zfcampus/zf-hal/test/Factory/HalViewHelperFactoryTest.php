<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace ZFTest\Hal\Factory;

use PHPUnit_Framework_TestCase as TestCase;
use ReflectionObject;
use Zend\ServiceManager\ServiceManager;
use Zend\Hydrator\HydratorPluginManager;
use Zend\View\Helper\ServerUrl;
use Zend\View\Helper\Url;
use ZF\Hal\Factory\HalViewHelperFactory;
use ZF\Hal\RendererOptions;

class HalViewHelperFactoryTest extends TestCase
{
    public function setupPluginManager($config = [])
    {
        $services = new ServiceManager();

        $services->setService('ZF\Hal\HalConfig', $config);

        if (isset($config['renderer']) && is_array($config['renderer'])) {
            $rendererOptions = new RendererOptions($config['renderer']);
        } else {
            $rendererOptions = new RendererOptions();
        }
        $services->setService('ZF\Hal\RendererOptions', $rendererOptions);

        $metadataMap = $this->getMock('ZF\Hal\Metadata\MetadataMap');
        $metadataMap
            ->expects($this->once())
            ->method('getHydratorManager')
            ->will($this->returnValue(new HydratorPluginManager()));

        $services->setService('ZF\Hal\MetadataMap', $metadataMap);

        $this->pluginManager = $this->getMock('Zend\ServiceManager\AbstractPluginManager');

        $this->pluginManager
            ->expects($this->at(1))
            ->method('get')
            ->with('ServerUrl')
            ->will($this->returnValue(new ServerUrl()));

        $this->pluginManager
            ->expects($this->at(2))
            ->method('get')
            ->with('Url')
            ->will($this->returnValue(new Url()));

        $this->pluginManager
            ->method('getServiceLocator')
            ->will($this->returnValue($services));
    }

    public function testInstantiatesHalViewHelper()
    {
        $this->setupPluginManager();

        $factory = new HalViewHelperFactory();
        $plugin = $factory->createService($this->pluginManager);

        $this->assertInstanceOf('ZF\Hal\Plugin\Hal', $plugin);
    }

    /**
     * @group fail
     */
    public function testOptionUseProxyIfPresentInConfig()
    {
        $options = [
            'options' => [
                'use_proxy' => true,
            ],
        ];

        $this->setupPluginManager($options);

        $factory = new HalViewHelperFactory();
        $halPlugin = $factory->createService($this->pluginManager);

        $r = new ReflectionObject($halPlugin);
        $p = $r->getProperty('serverUrlHelper');
        $p->setAccessible(true);
        $serverUrlPlugin = $p->getValue($halPlugin);
        $this->assertInstanceOf('Zend\View\Helper\ServerUrl', $serverUrlPlugin);

        $r = new ReflectionObject($serverUrlPlugin);
        $p = $r->getProperty('useProxy');
        $p->setAccessible(true);
        $useProxy = $p->getValue($serverUrlPlugin);
        $this->assertInternalType('boolean', $useProxy);
        $this->assertTrue($useProxy);
    }
}
