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

        // Travis fix (.*) due to weird console screen width that truncate to next line
        $this->assertRegExp('/Cache item "' . $key . '" set to "' . $value . '" for ' . $ttl . '/', $commandTester->getDisplay());
    }

    /**
     * @return \Phpfastcache\Bundle\Command\PhpfastcacheClearCommand
     */
    protected function getCommand()
    {
        return new PhpfastcacheSetCommand($this->phpfastcache, $this->phpfastcacheParameters);
    }
}
