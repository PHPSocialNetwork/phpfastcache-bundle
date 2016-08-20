<?php

namespace phpFastCache\Bundle\DataCollector;

use phpFastCache\Api as phpFastCacheApi;
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \Exception|null $exception
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
            $stats[ $instanceName ] = $cache->getStats();
            $instances[ $instanceName ] = [
              'driverName' => $cache->getDriverName(),
              'driverConfig' => $cache->getConfig(),
            ];
            $driverUsed[ $cache->getDriverName() ] = get_class($cache);
        }

        $this->data = [
          'apiVersion' => phpFastCacheApi::getVersion(),
          'driverUsed' => $driverUsed,
          'instances' => $instances,
          'stats' => $stats,
          'size' => $size,
          'hits' => [
            'read' => (int) CacheManager::$ReadHits,
            'write' => (int) CacheManager::$WriteHits,
          ],
          'coreConfig' => [
                'namespacePath' => CacheManager::getNamespacePath()
          ],
        ];
    }

    /**
     * @return mixed
     */
    public function getStats()
    {
        return $this->data[ 'stats' ];
    }

    /**
     * @return mixed
     */
    public function getInstances()
    {
        return $this->data[ 'instances' ];
    }

    /**
     * @return mixed
     */
    public function getDriverUsed()
    {
        return $this->data[ 'driverUsed' ];
    }

    /**
     * @return mixed
     */
    public function getHits()
    {
        return $this->data[ 'hits' ];
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->data[ 'size' ];
    }

    /**
     * @return mixed
     */
    public function getCoreConfig()
    {
        return $this->data[ 'coreConfig' ];
    }

    /**
     * @return mixed
     */
    public function getApiVersion()
    {
        return $this->data[ 'apiVersion' ];
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'phpfastcache';
    }
}