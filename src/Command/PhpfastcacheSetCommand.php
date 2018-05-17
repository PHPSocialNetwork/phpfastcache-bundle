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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PhpfastcacheSetCommand extends Command
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
          ->setName('phpfastcache:set')
          ->setDescription('Set phpfastcache cache value')
          ->addArgument(
            'driver',
            InputArgument::REQUIRED,
            'Cache name to clear'
          )
          ->addArgument(
            'key',
            InputArgument::REQUIRED,
            'Cache key'
          )
          ->addArgument(
            'value',
            InputArgument::REQUIRED,
            'Cache value'
          )
          ->addArgument(
            'ttl',
            InputArgument::OPTIONAL,
            'Cache ttl (in second)'
          )
          ->addOption(
            'auto-type-cast',
            'a',
            InputOption::VALUE_OPTIONAL,
            'Allows to automatically type-cast the value of the cache item.',
            1
          )
        ;
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
        $cacheValue = $input->getArgument('value');
        $cacheTtl = $input->getArgument('ttl');
        $autoTypeCast = (bool) $input->getOption('auto-type-cast');

        if (\array_key_exists($driver, $caches[ 'drivers' ])) {
            $io->section($driver);
            $driverInstance = $this->phpfastcache->get($driver);
            $cacheItem = $driverInstance->getItem($cacheKey);
            $castedCacheValue = $this->autoTypeCast($cacheValue);

            $cacheItem->set($autoTypeCast ? $castedCacheValue : $cacheValue);

            if($cacheTtl){
                if(\is_numeric($cacheTtl)){
                    $cacheItem->expiresAfter($cacheTtl);
                }else{
                    $io->error(\sprintf('Invalid ttl value format "%s", must be a valid integer, aborting...', $cacheTtl));
                    return;
                }
            }

            if(!$autoTypeCast || $castedCacheValue === $cacheValue) {
                $io->success(\sprintf(
                  'Cache item "%s" set to "%s" for %d seconds',
                  $cacheKey,
                  $cacheValue,
                  $cacheItem->getTtl()
                ));
            } else {
                $io->success(\sprintf(
                  'Cache item "%s" set to "%s" for %d seconds (automatically type-casted to %s)',
                  $cacheKey,
                  $cacheValue,
                  $cacheItem->getTtl(),
                  \gettype($castedCacheValue)
                ));
            }

            $driverInstance->save($cacheItem);
        } else {
            $io->error("Cache instance {$driver} does not exists");
        }
    }

    /**
     * @param $string
     * @return bool|float|int|null|array
     */
    protected function autoTypeCast($string)
    {
        if(\in_array(\strtolower($string), ['true', 'false'], true)){
            return $string === 'true';
        }

        if(\strtolower($string) === 'null'){
            return null;
        }

        if(\is_numeric($string))
        {
            if(\strpos($string, '.') !== false){
                return (float) $string;
            }
            return (int) $string;
        }

        $jsonArray = json_decode($string, true);
        if(json_last_error() === JSON_ERROR_NONE){
            return (array) $jsonArray;
        }

        return $string;
    }
}