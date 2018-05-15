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

use Phpfastcache\Bundle\Command\PhpfastcacheCommand;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * PhpfastcacheCommandTest
 */
class PhpfastcacheCommandTest extends CommandTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testCommandOptionWithoutSpecifiedDriver()
    {
        $command = $this->application->find('phpfastcache:clear');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
          'command' => $command->getName(),
          '--no-interaction' => true
        ]);

        $this->assertRegExp('/All caches instances got cleared/', $commandTester->getDisplay());
    }

    public function testCommandOptionWithExistingDriver()
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

    public function testCommandOptionWithNotExistingDriver()
    {
        $command = $this->application->find('phpfastcache:clear');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
          'command' => $command->getName(),
          'driver' => 'notExisting',
          '--no-interaction' => true
        ]);

        $this->assertRegExp('/Cache instance notExisting does not exists/', $commandTester->getDisplay());
    }

    /**
     * @return \Phpfastcache\Bundle\Command\PhpfastcacheCommand
     */
    protected function getCommand()
    {
        return new PhpfastcacheCommand($this->phpfastcache, $this->phpfastcacheParameters);
    }
}
