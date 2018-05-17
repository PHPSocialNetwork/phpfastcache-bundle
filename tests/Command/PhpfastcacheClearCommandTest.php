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

use Phpfastcache\Bundle\Command\PhpfastcacheClearCommand;
use Phpfastcache\CacheManager;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * PhpfastcacheCommandTest
 */
class PhpfastcacheClearCommandTest extends CommandTestCase
{

    public function setUp()
    {
        CacheManager::clearInstances();
        putenv('COLUMNS=200');// Prevent broken console rendering  with unit tests
        parent::setUp();
    }

    public function testCommandClearAllCacheInstances()
    {
        $command = $this->application->find('phpfastcache:clear');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
          'command' => $command->getName(),
          '--no-interaction' => true
        ]);

        $this->assertRegExp('/All caches instances got cleared/', $commandTester->getDisplay());
    }

    public function testCommandClearSingleCacheInstance()
    {
        $command = $this->application->find('phpfastcache:clear');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
          'command' => $command->getName(),
          'driver' => 'filecache',
          '--no-interaction' => true
        ]);

        $this->assertRegExp('/Cache instance filecache cleared/', $commandTester->getDisplay());

        $commandTester->execute([
          'command' => $command->getName(),
          'driver' => 'staticcache',
          '--no-interaction' => true
        ]);

        $this->assertRegExp('/Cache instance staticcache cleared/', $commandTester->getDisplay());
    }

    public function testCommandClearInvalidCacheInstance()
    {
        $command = $this->application->find('phpfastcache:clear');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
          'command' => $command->getName(),
          'driver' => 'invalidCache',
          '--no-interaction' => true
        ]);

        $this->assertRegExp('/Cache instance invalidCache does not exists/', $commandTester->getDisplay());
    }

    /**
     * @return \Phpfastcache\Bundle\Command\PhpfastcacheClearCommand
     */
    protected function getCommand()
    {
        return new PhpfastcacheClearCommand($this->phpfastcache, $this->phpfastcacheParameters);
    }
}
