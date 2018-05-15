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

class PhpfastcacheGetCommand extends Command
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
          ->setName('phpfastcache:get')
          ->setDescription('Get phpfastcache cache value')
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
            $cacheItem = $this->phpfastcache->get($driver)->getItem($cacheKey);

            if($cacheItem->isHit()){
                $cacheItemValue = $cacheItem->get();
                if(!\is_object($cacheItemValue)){
                    ob_start();
                    var_dump($cacheItemValue);
                    $content = ob_get_contents();
                    ob_end_clean();
                    $io->write('<bg=green;fg=black>[HIT]</> ' . $content);
                }else{
                    $io->write('<bg=green;fg=black>[HIT]</> (object) ' . \get_class($cacheItemValue));
                }
                $io->write('This item will expires in <fg=green>' . $cacheItem->getTtl() .'</> second(s)');
            }else{
                $io->write('<bg=yellow;fg=red>[MISS]</> The cache item "' . $cacheKey . '" does not (yet) exists !');
            }
        } else {
            $io->error("Cache instance {$driver} does not exists");
        }
    }
}