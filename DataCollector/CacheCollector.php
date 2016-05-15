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
        $this->data = [
            'cache' => $this->cache,
        ];
    }

    public function getCache()
    {
        /**
         * @var  $name
         * @var ExtendedCacheItemPoolInterface $cache
         */
        foreach($this->data['cache'] as $name => $cache) {
            var_dump($cache->getStats());
        }
        return $this->data['cache'];
    }

    public function getName()
    {
        return 'phpfastcache.request_collector';
    }
}
