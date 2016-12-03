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

namespace phpFastCache\Bundle\DataCollector;

use phpFastCache\Api as phpFastCacheApi;
use phpFastCache\Bundle\phpFastCacheBundle;
use phpFastCache\Bundle\Service\Cache;
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
     * @var array
     */
    private $twig_cache_blocks = [];

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
          'twigCacheBlocks' => $this->twig_cache_blocks,
          'apiVersion' => phpFastCacheApi::getVersion(),
          'bundleVersion' => phpFastCacheBundle::VERSION,
          'apiChangelog' => phpFastCacheApi::getChangelog(),
          'driverUsed' => $driverUsed,
          'instances' => $instances,
          'stats' => $stats,
          'size' => $size,
          'hits' => [
            'read' => (int) CacheManager::$ReadHits,
            'write' => (int) CacheManager::$WriteHits,
          ],
          'coreConfig' => [
            'namespacePath' => CacheManager::getNamespacePath(),
          ],
          'projectConfig' => [
            'twig_driver' => $this->cache->getConfig()['twig_driver'],
            'twig_block_debug' => $this->cache->getConfig()['twig_block_debug'],
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
    public function getProjectConfig()
    {
        return $this->data[ 'projectConfig' ];
    }

    /**
     * @return mixed
     */
    public function getApiVersion()
    {
        return $this->data[ 'apiVersion' ];
    }

    /**
     * @return mixed
     */
    public function getBundleVersion()
    {
        return $this->data[ 'bundleVersion' ];
    }

    /**
     * @return mixed
     */
    public function getApiChangelog()
    {
        return $this->data[ 'apiChangelog' ];
    }

    /**
     * @param string $blockName
     * @param array $cacheBlock
     * @return $this
     */
    public function setTwigCacheBlock($blockName, array $cacheBlock)
    {
        if(isset($this->twig_cache_blocks[$blockName])){
            $this->twig_cache_blocks[$blockName] = array_merge($this->twig_cache_blocks[$blockName], $cacheBlock);
        }else{
            $this->twig_cache_blocks[$blockName] = $cacheBlock;
        }


        return $this;
    }

    /**
     * @return array
     */
    public function getTwigCacheBlocks()
    {
        return $this->data[ 'twigCacheBlocks' ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'phpfastcache';
    }
}