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

declare(strict_types=1);

namespace Phpfastcache\Bundle\Tests\Functional;

use Phpfastcache\Bundle\Tests\Functional\App\Kernel;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class IntegrationTest
 * @package Phpfastcache\Bundle\Tests\Functional
 */
class IntegrationTest extends WebTestCase
{
    /** @var Client */
    private $client;

    protected function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIntegration()
    {
        $response = $this->profileRequest('GET', '/');

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @param string $method
     * @param string $uri
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function profileRequest(string $method, string $uri): Response
    {
        $client = $this->client;
        $client->enableProfiler();
        $client->request($method, $uri);

        return $client->getResponse();
    }

    /**
     * Manage schema and cleanup chores
     */
    public static function setUpBeforeClass()
    {
        static::deleteTmpDir();
        $kernel = static::createClient()->getKernel();

        /**
         * Cleanup Phpfastcache cache
         */
        $application = new Application($kernel);
        $command = $application->find('phpfastcache:clear');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
          'command' => $command->getName(), '--no-interaction' => true
        ]);
    }

    public static function tearDownAfterClass()
    {
        static::deleteTmpDir();
    }

    protected static function deleteTmpDir()
    {
        if (!file_exists($dir = __DIR__ . '/App/var')) {
            return;
        }

        $fs = new Filesystem();
        $fs->remove($dir);
    }

    /**
     * @return string
     */
    protected static function getKernelClass()
    {
        require_once __DIR__ . '/App/Kernel.php';

        return Kernel::class;
    }
}