<?php

namespace phpFastCache\Bundle\Service;

use phpFastCache\Cache\ExtendedCacheItemPoolInterface;
use phpFastCache\CacheManager;
use phpFastCache\Exceptions\phpFastCacheDriverException;

/**
 * Class Cache
 * @package phpFastCache\Bundle\Service
 */
class Cache
{
    /**
     * Contains all cache instances
     *
     * @var \phpFastCache\Cache\ExtendedCacheItemPoolInterface[]
     */
    private $cacheInstances = [];

    /**
     * Cache constructor.
     *
     * @param $drivers
     *
     * @throws \phpFastCache\Exceptions\phpFastCacheDriverException
     */
    public function __construct($drivers)
    {
        foreach ($drivers['drivers'] as $name => $driver) {
            dump($driver);
            $this->createInstance($name, CacheManager::getInstance($driver['type'], $driver['parameters']));
        }
    }

    /**
     * Set a new cache instance
     *
     * @param string                         $name
     * @param ExtendedCacheItemPoolInterface $instance
     *
     * @throws \phpFastCache\Exceptions\phpFastCacheDriverException
     */
    public function createInstance($name, ExtendedCacheItemPoolInterface $instance)
    {
        if (array_key_exists($name, $this->cacheInstances) && $this->cacheInstances[$name] instanceof ExtendedCacheItemPoolInterface) {
            throw new phpFastCacheDriverException("Driver named $name already exists");
        }
        $this->cacheInstances[$name] = $instance;
    }

    /**
     * get a cache instance
     *
     * @param string $name Name of configured driver
     *
     * @return \phpFastCache\Cache\ExtendedCacheItemPoolInterface
     *
     * @throws \phpFastCache\Exceptions\phpFastCacheDriverException
     */
    public function get($name)
    {
        if (!array_key_exists($name, $this->cacheInstances)) {
            throw new phpFastCacheDriverException("Driver named $name not exists");
        }
        if (!$this->cacheInstances[$name] instanceof ExtendedCacheItemPoolInterface) {
            throw new phpFastCacheDriverException("Driver named $name already instanciated");
        }

        return $this->cacheInstances[$name];
    }
}