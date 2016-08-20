<?php

namespace phpFastCache\Bundle\Service;

use phpFastCache\Core\Pool\ExtendedCacheItemPoolInterface;
use phpFastCache\CacheManager;
use phpFastCache\Exceptions\phpFastCacheDriverException;

/**
 * Class Cache
 * @package phpFastCache\Bundle\Service
 */
class Cache
{
    private $drivers = [];

    /**
     * Contains all cache instances
     *
     * @var \phpFastCache\Core\Pool\ExtendedCacheItemPoolInterface[]
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
        $this->drivers = (array) $drivers[ 'drivers' ];
    }

    /**
     * Set a new cache instance
     *
     * @param string $name
     * @param ExtendedCacheItemPoolInterface $instance
     *
     * @throws \phpFastCache\Exceptions\phpFastCacheDriverException
     */
    public function createInstance($name, $instance)
    {
        if (array_key_exists($name, $this->cacheInstances) && $this->cacheInstances[ $name ] instanceof ExtendedCacheItemPoolInterface) {
            throw new phpFastCacheDriverException("Cache instance '{$name}' already exists");
        }
        $this->cacheInstances[ $name ] = $instance;
    }

    /**
     * get a cache instance
     *
     * @param string $name Name of configured driver
     *
     * @return \phpFastCache\Core\Pool\ExtendedCacheItemPoolInterface
     *
     * @throws \phpFastCache\Exceptions\phpFastCacheDriverException
     */
    public function get($name)
    {
        if (!array_key_exists($name, $this->cacheInstances)) {
            if (array_key_exists($name, $this->drivers)) {
                $this->createInstance($name, CacheManager::getInstance($this->drivers[ $name ][ 'type' ], $this->drivers[ $name ][ 'parameters' ]));
                if (!$this->cacheInstances[ $name ] instanceof ExtendedCacheItemPoolInterface) {
                    throw new phpFastCacheDriverException("Cache instance '{$name}' does not implements ExtendedCacheItemPoolInterface");
                }
            } else {
                throw new phpFastCacheDriverException("Cache instance '{$name}' not exists, check your config.yml");
            }
        }

        return $this->cacheInstances[ $name ];
    }

    /**
     * Return all cache instances
     *
     * @return \phpFastCache\Core\Pool\ExtendedCacheItemPoolInterface[]
     */
    public function getInstances()
    {
        return $this->cacheInstances;
    }
}