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

namespace phpFastCache\Bundle\Command;

use phpFastCache\Cache\ExtendedCacheItemPoolInterface;
use phpFastCache\Exceptions\phpFastCacheDriverCheckException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class phpFastCacheCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('phpfastcache:clear')
            ->setDescription('Clear phpfastcache cache')
            ->addArgument(
                'driver',
                InputArgument::OPTIONAL,
                'Cache name to clear'
            )
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $failedInstances = [];
        $io = new SymfonyStyle($input, $output);

        $phpFastCache = $this->getContainer()->get('phpfastcache');
        $driver = $input->getArgument('driver');

        $output->writeln("<bg=yellow;fg=red>Clearing cache operation can take a while, please be patient...</>");

        $callback = function($name) use ($phpFastCache, $output, &$failedInstances)
        {
            try{
                if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                    $output->writeln("<fg=yellow>Clearing instance {$name} cache...</>");
                }
                $phpFastCache->get($name)->clear();
                if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                    $output->writeln("<fg=green>Cache instance {$name} cleared</>");
                }
            }catch (phpFastCacheDriverCheckException $e){
                $failedInstances[] = $name;
                if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                    $output->writeln("<fg=red>Cache instance {$name} not cleared, got exception: " . "<bg=red;options=bold>" . $e->getMessage() ."</>");
                }else{
                    $output->writeln("<fg=red>Cache instance {$name} not cleared (increase verbosity to get more information).</>");
                }
            }
        };
        $caches = $this->getContainer()->getParameter('phpfastcache');

        if($driver) {
            if(array_key_exists($driver, $caches['drivers'])){
                $callback($driver);
                if(!count($failedInstances)){
                    $io->success("Cache instance {$driver} cleared");
                }else{
                    $io->error("Cache instance {$driver} not cleared");
                }
            }else{
                $io->error("Cache instance {$driver} does not exists");
            }
        } else {
            foreach($caches['drivers'] as $name => $parameters) {
                $callback($name);
            }
            if(!count($failedInstances)){
                $io->success('All caches instances got cleared');
            }else{
                $io->success('Almost all caches instances got cleared, except these: ' . implode(', ', $failedInstances));
            }
        }
    }
}