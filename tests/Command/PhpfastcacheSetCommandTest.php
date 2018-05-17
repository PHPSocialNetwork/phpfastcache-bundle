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

use Phpfastcache\Bundle\Command\PhpfastcacheSetCommand;
use Phpfastcache\CacheManager;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * PhpfastcacheCommandTest
 */
class PhpfastcacheSetCommandTest extends CommandTestCase
{

    public function setUp()
    {
        CacheManager::clearInstances();
        putenv('COLUMNS=200');// Prevent broken console rendering  with unit tests
        parent::setUp();
    }

    public function testCommandSetCacheItem()
    {
        $value = \md5(\random_bytes(255));
        $ttl = \random_int(2, 10);
        $key = 'test' . \random_int(1000, 1999);

        $command = $this->application->find('phpfastcache:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
          'command' => $command->getName(),
          'driver' => 'filecache',
          'key' => $key,
          'value' => $value,
          'ttl' => $ttl,
          '--no-interaction' => true
        ]);

        $this->assertContains('Cache item "' . $key . '" set to "' . $value . '" for ' . $ttl . ' seconds', $commandTester->getDisplay());
    }

    public function testCommandSetCacheItemWithAutomaticTypeCastingBoolean()
    {
        $value = 'true';
        $ttl = \random_int(2, 10);
        $key = 'test' . \random_int(1000, 1999);

        $command = $this->application->find('phpfastcache:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
          'command' => $command->getName(),
          'driver' => 'filecache',
          'key' => $key,
          'value' => $value,
          'ttl' => $ttl,
          '-a' => 1,
          '--no-interaction' => true
        ]);

        $this->assertContains('Cache item "' . $key . '" set to "' . $value . '" for ' . $ttl . ' seconds', $commandTester->getDisplay());
        $this->assertContains('(automatically type-casted to boolean)', $commandTester->getDisplay(), true);
    }

    public function testCommandSetCacheItemWithAutomaticTypeCastingInteger()
    {
        $value = '1337';
        $ttl = \random_int(2, 10);
        $key = 'test' . \random_int(1000, 1999);

        $command = $this->application->find('phpfastcache:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
          'command' => $command->getName(),
          'driver' => 'filecache',
          'key' => $key,
          'value' => $value,
          'ttl' => $ttl,
          '-a' => 1,
          '--no-interaction' => true
        ]);

        $this->assertContains('Cache item "' . $key . '" set to "' . $value . '" for ' . $ttl . ' seconds', $commandTester->getDisplay());
        $this->assertContains('(automatically type-casted to integer)', $commandTester->getDisplay(), true);
    }

    public function testCommandSetCacheItemWithAutomaticTypeCastingFloat()
    {
        $value = '1337.666';
        $ttl = \random_int(2, 10);
        $key = 'test' . \random_int(1000, 1999);

        $command = $this->application->find('phpfastcache:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
          'command' => $command->getName(),
          'driver' => 'filecache',
          'key' => $key,
          'value' => $value,
          'ttl' => $ttl,
          '-a' => 1,
          '--no-interaction' => true
        ]);

        $this->assertContains('Cache item "' . $key . '" set to "' . $value . '" for ' . $ttl . ' seconds', $commandTester->getDisplay());
        $this->assertContains('(automatically type-casted to double)', $commandTester->getDisplay(), true);
    }

    public function testCommandSetCacheItemWithAutomaticTypeCastingNull()
    {
        $value = 'null';
        $ttl = \random_int(2, 10);
        $key = 'test' . \random_int(1000, 1999);

        $command = $this->application->find('phpfastcache:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
          'command' => $command->getName(),
          'driver' => 'filecache',
          'key' => $key,
          'value' => $value,
          'ttl' => $ttl,
          '-a' => 1,
          '--no-interaction' => true
        ]);

        $this->assertContains('Cache item "' . $key . '" set to "' . $value . '" for ' . $ttl . ' seconds', $commandTester->getDisplay());
        $this->assertContains('(automatically type-casted to NULL)', $commandTester->getDisplay(), true);
    }

    public function testCommandSetCacheItemWithAutomaticTypeCastingJson()
    {
        $value = '{"test": 1337}';
        $ttl = \random_int(2, 10);
        $key = 'test' . \random_int(1000, 1999);

        $command = $this->application->find('phpfastcache:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
          'command' => $command->getName(),
          'driver' => 'filecache',
          'key' => $key,
          'value' => $value,
          'ttl' => $ttl,
          '-a' => 1,
          '--no-interaction' => true
        ]);

        $this->assertContains('Cache item "' . $key . '" set to "' . $value . '" for ' . $ttl . ' seconds', $commandTester->getDisplay());
        $this->assertContains('(automatically type-casted to array)', $commandTester->getDisplay(), true);
    }

    public function testCommandSetCacheItemWithoutAutomaticTypeCasting()
    {
        $value = 'null';
        $ttl = \random_int(2, 10);
        $key = 'test' . \random_int(1000, 1999);

        $command = $this->application->find('phpfastcache:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
          'command' => $command->getName(),
          'driver' => 'filecache',
          'key' => $key,
          'value' => $value,
          'ttl' => $ttl,
          '-a' => 0,
          '--no-interaction' => true
        ]);

        $this->assertContains('Cache item "' . $key . '" set to "' . $value . '" for ' . $ttl . ' seconds', $commandTester->getDisplay());
        $this->assertNotContains('(automatically type-casted to NULL)', $commandTester->getDisplay(), true);
    }

    /**
     * @return \Phpfastcache\Bundle\Command\PhpfastcacheClearCommand
     */
    protected function getCommand()
    {
        return new PhpfastcacheSetCommand($this->phpfastcache, $this->phpfastcacheParameters);
    }
}
