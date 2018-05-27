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
 * @author Khoa Bui (khoaofgod)  <khoaofgod@gmail.com> http://www.phpfastcache.com
 *
 */
declare(strict_types=1);

namespace Phpfastcache\Bundle\Command;

use Phpfastcache\Bundle\Service\Phpfastcache;
use Phpfastcache\Exceptions\PhpfastcacheDriverCheckException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PhpfastcacheDelCommand extends Command
{
    /**
     * @var \Phpfastcache\Bundle\Service\Phpfastcache
     */
    protected $phpfastcache;

    /**
     * @var
     */
    protected $parameters;

    /**
     * PhpfastcacheCommand constructor.
     * @param \Phpfastcache\Bundle\Service\Phpfastcache $phpfastcache
     * @param $parameters
     */
    public function __construct(Phpfastcache $phpfastcache, $parameters)
    {
        $this->phpfastcache = $phpfastcache;
        $this->parameters = $parameters;

        parent::__construct();
    }

    protected function configure()
    {
        $this
          ->setName('phpfastcache:del')
          ->setAliases([ 'phpfastcache:delete', 'phpfastcache:rem', 'phpfastcache:remove'])
          ->setDescription('Delete phpfastcache cache value')
          ->addArgument(
            'driver',
            InputArgument::REQUIRED,
            'Cache name to clear'
          )
          ->addArgument(
            'key',
            InputArgument::REQUIRED,
            'Cache key'
          );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $caches = $this->parameters;

        $driver = $input->getArgument('driver');
        $cacheKey = $input->getArgument('key');

        if (\array_key_exists($driver, $caches[ 'drivers' ])) {
            $io->section($driver);
            $driverInstance = $this->phpfastcache->get($driver);
            $cacheItem = $driverInstance->getItem($cacheKey);

            if(!$cacheItem->isHit()){
                $io->note(\sprintf('Cache item "%s" does not exists in the cache', $cacheKey));
            }else{
                $driverInstance->deleteItem($cacheItem->getKey());
                $io->success(\sprintf('Cache item "%s" has been deleted from cache', $cacheKey));
            }
        } else {
            $io->error("Cache instance {$driver} does not exists");
        }
    }
}