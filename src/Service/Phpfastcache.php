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

namespace Phpfastcache\Bundle\Service;

use Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface;
use Phpfastcache\CacheManager;
use Phpfastcache\Exceptions\PhpfastcacheDriverException;
use Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class Cache
 * @package Phpfastcache\Bundle\Service
 */
class Phpfastcache
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * @var Stopwatch
     */
    protected $stopwatch;

    /**
     * Contains all cache instances
     *
     * @var \Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface[]
     */
    private $cacheInstances = [];

    /**
     * Cache constructor.
     *
     * @param array $config
     * @param Stopwatch $stopwatch
     *
     * @throws \Phpfastcache\Exceptions\phpFastCacheDriverException
     */
    public function __construct(array $config, Stopwatch $stopwatch = null)
    {
        $this->config = $config;
        $this->stopwatch = $stopwatch;
    }

    /**
     * Set a new cache instance
     *
     * @param string $name
     * @param ExtendedCacheItemPoolInterface $instance
     *
     * @throws \Phpfastcache\Exceptions\phpFastCacheDriverException
     */
    public function createInstance(string $name, ExtendedCacheItemPoolInterface $instance)
    {
        if (\array_key_exists($name, $this->cacheInstances) && $this->cacheInstances[ $name ] instanceof ExtendedCacheItemPoolInterface) {
            throw new PhpfastcacheDriverException("Cache instance '{$name}' already exists");
        }
        $this->cacheInstances[ $name ] = $instance;
    }

    /**
     * get a cache instance
     *
     * @param string $name Name of configured driver
     *
     * @return ExtendedCacheItemPoolInterface
     *
     * @throws \Phpfastcache\Exceptions\phpFastCacheDriverException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException
     */
    public function get(string $name): ExtendedCacheItemPoolInterface
    {
        if ($this->stopwatch) {
            $this->stopwatch->start(__METHOD__ . "('{$name}')");
        }

        if (!\array_key_exists($name, $this->cacheInstances)) {
            if (\array_key_exists($name, $this->config[ 'drivers' ])) {
                $driverClass = CacheManager::getDriverClass($this->config[ 'drivers' ][ $name ][ 'type' ]);
                if (\is_a($driverClass, ExtendedCacheItemPoolInterface::class, true)){
                    $configClass = $driverClass::getConfigClass();
                    if(\class_exists($configClass)){
                        $this->createInstance(
                          $name,
                          CacheManager::getInstance(
                            $this->config[ 'drivers' ][ $name ][ 'type' ],
                            new $configClass($this->config[ 'drivers' ][ $name ][ 'parameters' ])
                          )
                        );
                    }else{
                        throw new PhpfastcacheInvalidConfigurationException('Invalid configuration class name: ' . $configClass);
                    }
                }

                if (!isset($this->cacheInstances[ $name ]) || !($this->cacheInstances[ $name ] instanceof ExtendedCacheItemPoolInterface)) {
                    throw new PhpfastcacheDriverException("Cache instance '{$name}' does not implements ExtendedCacheItemPoolInterface");
                }
            } else {
                throw new PhpfastcacheDriverException("Cache instance '{$name}' not exists, check your config.yml");
            }
        }

        if ($this->stopwatch) {
            $this->stopwatch->stop(__METHOD__ . "('{$name}')");
        }
        return $this->cacheInstances[ $name ];
    }

    /**
     * @return \Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface
     */
    public function getTwigCacheInstance(): ExtendedCacheItemPoolInterface
    {
        return $this->get($this->config[ 'twig_driver' ]);
    }

    /**
     * Return all cache instances
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Return all cache instances
     *
     * @return \Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface[]
     */
    public function getInstances(): array
    {
        return $this->cacheInstances;
    }
}