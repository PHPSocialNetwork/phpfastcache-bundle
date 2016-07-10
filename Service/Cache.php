<?php

namespace phpFastCache\Bundle\Service;

use phpFastCache\Cache\ExtendedCacheItemPoolInterface;
use phpFastCache\CacheManager;
use phpFastCache\Exceptions\phpFastCacheDriverException;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class Cache
 * @package phpFastCache\Bundle\Service
 */
class Cache
{
    /**
     * @var array
     */
    private $drivers = [];

    /**
     * @var Stopwatch
     */
    protected $stopwatch;

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
     * @param Stopwatch $stopwatch
     *
     * @throws \phpFastCache\Exceptions\phpFastCacheDriverException
     */
    public function __construct($drivers, Stopwatch $stopwatch = null)
    {
        $this->drivers = (array) $drivers[ 'drivers' ];
        $this->stopwatch = $stopwatch;
    }

    /**
     * Set a new cache instance
     *
     * @param string $name
     * @param ExtendedCacheItemPoolInterface $instance
     *
     * @throws \phpFastCache\Exceptions\phpFastCacheDriverException
     */
    public function createInstance($name, ExtendedCacheItemPoolInterface $instance)
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
     * @return \phpFastCache\Cache\ExtendedCacheItemPoolInterface
     *
     * @throws \phpFastCache\Exceptions\phpFastCacheDriverException
     */
    public function get($name)
    {
        if ($this->stopwatch) {
            $this->stopwatch->start(__METHOD__ . "('{$name}')");
        }

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

        if ($this->stopwatch) {
            $this->stopwatch->stop(__METHOD__ . "('{$name}')");
        }
        return $this->cacheInstances[ $name ];
    }

    /**
     * Return all cache instances
     *
     * @return \phpFastCache\Cache\ExtendedCacheItemPoolInterface[]
     */
    public function getInstances()
    {
        return $this->cacheInstances;
    }
}