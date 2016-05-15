<?php

namespace phpFastCache\Bundle\DataCollector;

use phpFastCache\Bundle\Service\Cache;
use phpFastCache\Cache\ExtendedCacheItemPoolInterface;
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
        }

        $this->data = [
            'instances' => $instances,
            'stats' => $stats,
            'size'   => $size
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
