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
        /** @var  $cache */
        foreach ($this->cache->getInstances() as $cache) {
            if ($cache->getStats()->getSize()) {
                $size += $cache->getStats()->getSize();
            }
        }
        $this->data = [
            'caches' => $this->cache->getInstances(),
            'size'   => $size,
        ];
    }

    public function getCaches()
    {
        /**
         * @var                                $name
         * @var ExtendedCacheItemPoolInterface $cache
         */
        return $this->data['caches'];
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
        return 'phpfastcache.request_collector';
    }
}
