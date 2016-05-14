<?php

namespace phpFastCache\Bundle\DependencyInjection;

use phpFastCache\Exceptions\phpFastCacheDriverException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class phpFastCacheExtension
 * @package phpFastCache\Bundle\DependencyInjection
 */
class phpFastCacheExtension extends Extension
{
    /**
     * {@inheritDoc}
     *
     * @throws \phpFastCache\Exceptions\phpFastCacheDriverCheckException
     * @throws \phpFastCache\Exceptions\phpFastCacheDriverException
     * @throws \Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['drivers'] as $name => $driver) {
            $class = "phpFastCache\\Drivers\\" . $driver['type'] . '\Driver';
            foreach ($driver['parameters'] as $parameter_name => $parameter) {
                if (!$class::isValidOption($parameter_name, $parameter)) {
                    throw new phpFastCacheDriverException("Option $parameter_name in driver {$driver['type']} doesn't exists");
                }
            }
        }

        $container->setParameter('phpfastcache', $config);

    }
}
