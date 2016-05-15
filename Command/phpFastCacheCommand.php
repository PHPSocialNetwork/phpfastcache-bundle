<?php
namespace phpFastCache\Bundle\Command;

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
        $io = new SymfonyStyle($input, $output);

        $phpFastCache = $this->getContainer()->get('phpfastcache');
        $driver = $input->getArgument('driver');
        if($driver) {
            $phpFastCache->get($driver)->clear();
            $io->success("Cache {$driver} cleared");
        } else {
            $caches = $this->getContainer()->getParameter('phpfastcache');
            foreach($caches['drivers'] as $name => $parameters) {
                if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                    $output->writeln("Cache {$name} cleared");
                }
                $phpFastCache->get($name)->clear();
            }
            $io->success('All caches cleared');
        }
    }
}