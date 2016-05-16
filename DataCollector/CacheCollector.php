<?php

namespace phpFastCache\Bundle\DataCollector;

use phpFastCache\Bundle\Service\Cache;
use phpFastCache\Cache\ExtendedCacheItemPoolInterface;
use phpFastCache\CacheManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class CacheCollector extends DataCollector
{
    /**
     * @var \phpFastCache\Bundle\Service\Cache
     */
    private $cache;

    /**
     * CacheCollector constructor.
     *
     * @param \phpFastCache\Bundle\Service\Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \Exception|null                            $exception
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $size = 0;
        $stats = [];
        $instances = [];
        $driverUsed = [];

        /** @var  $cache */
        foreach ($this->cache->getInstances() as $instanceName => $cache) {
            if ($cache->getStats()->getSize()) {
                $size += $cache->getStats()->getSize();
            }
            $stats[$instanceName] = $cache->getStats();
            $instances[$instanceName] = [
              'driverName' => $cache->getDriverName(),
              'driverConfig' => $cache->getConfig()
            ];
            $driverUsed[$cache->getDriverName()] = get_class($cache);
        }

        $this->data = [
          'driverUsed' => $driverUsed,
          'instances' => $instances,
          'stats' => $stats,
          'size'   => $size,
          'hits' => [
              'read' => (int) CacheManager::$ReadHits,
              'write' => (int) CacheManager::$WriteHits,
          ]
        ];
    }

    public function getStats()
    {
        return $this->data['stats'];
    }

    public function getInstances()
    {
        return $this->data['instances'];
    }

    public function getDriverUsed()
    {
        return $this->data['driverUsed'];
    }

    public function getHits()
    {
        return $this->data['hits'];
    }

    public function getSize()
    {
        /**
         * @var                                $name
         * @var ExtendedCacheItemPoolInterface $cache
         */
        return $this->data['size'];
    }

    public function getName()
    {
        return 'phpfastcache';
    }
}