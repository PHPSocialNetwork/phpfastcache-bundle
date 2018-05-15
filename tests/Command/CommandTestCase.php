<?php

/**
 *
 * This file is part of phpFastCache.
 *
 * @license MIT License (MIT)
 *
 * For full copyright and license information, please see the docs/CREDITS.txt file.
 *
 * @author Georges.L (Geolim4)  <contact@geolim4.com>
 * @author PastisD https://github.com/PastisD
 *
 */

namespace Phpfastcache\Bundle\Tests\Command;

use Phpfastcache\Bundle\Service\Phpfastcache;
use Phpfastcache\Core\Item\ExtendedCacheItemInterface;
use Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Base Class for command tests
 *
 * @author Sebastian Göttschkes <sebastian.goettschkes@googlemail.com>
 */
abstract class CommandTestCase extends TestCase
{

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Console\Application
     */
    protected $application;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface|MockObject
     */
    protected $container;

    /**
     * @var \Phpfastcache\Bundle\Service\Phpfastcache|MockObject
     */
    protected $phpfastcache;


    /**
     * @var array
     */
    protected $phpfastcacheParameters = [];

    /**
     * SetUp called before each tests, setting up the environment (application, globally used mocks)
     */
    public function setUp()
    {
        $this->container = $this->getMockBuilder('\\Symfony\\Component\\DependencyInjection\\ContainerInterface')->getMock();

        /** @var Kernel|MockObject $kernel */
        $kernel = $this->getMockBuilder('\\Symfony\\Component\\HttpKernel\\Kernel')
          ->disableOriginalConstructor()
          ->getMock();
        $kernel->expects($this->once())
          ->method('getBundles')
          ->will($this->returnValue([]));
        $kernel->expects($this->any())
          ->method('getContainer')
          ->will($this->returnValue($this->container));
        $this->application = new Application($kernel);

        $this->phpfastcacheParameters = [
          'twig_driver' => 'filecache',
          'twig_block_debug' => true,
          'drivers' =>
            [
              'filecache' =>
                [
                  'type' => 'Files',
                  'parameters' =>
                    [
                      'path' => \sys_get_temp_dir() . '/phpfastcache/',
                    ],
                ],
              'staticcache' =>
                [
                  'type' => 'Memstatic',
                  'parameters' => [],
                ],
            ],
        ];

        $this->phpfastcache = new Phpfastcache($this->phpfastcacheParameters);

        $command = $this->getCommand();
        $this->application->add($command);
    }

    /**
     * Method used by the implementation of the command test to return the actual command object
     *
     * @return mixed The command to be tested
     */
    abstract protected function getCommand();
}
