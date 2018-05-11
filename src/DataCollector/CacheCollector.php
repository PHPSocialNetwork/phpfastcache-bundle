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

namespace Phpfastcache\Bundle\DataCollector;

use Phpfastcache\Api as PhpfastcacheApi;
use Phpfastcache\Bundle\PhpfastcacheBundle;
use Phpfastcache\Bundle\Service\Phpfastcache;
use Phpfastcache\CacheManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class CacheCollector extends DataCollector
{
    /**
     * @var \Phpfastcache\Bundle\Service\Phpfastcache
     */
    private $phpfastcache;

    /**
     * @var array
     */
    private $twig_cache_blocks = [];

    /**
     * CacheCollector constructor.
     *
     * @param \Phpfastcache\Bundle\Service\Phpfastcache $phpfastcache
     */
    public function __construct(Phpfastcache $phpfastcache)
    {
        $this->phpfastcache = $phpfastcache;
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
        foreach ($this->phpfastcache->getInstances() as $instanceName => $cache) {
            if ($cache->getStats()->getSize()) {
                $size += $cache->getStats()->getSize();
            }
            $stats[ $instanceName ] = $cache->getStats();
            $instances[ $instanceName ] = [
              'driverName' => $cache->getDriverName(),
              'configClassName' => \get_class( $cache->getConfig()),
              'driverConfig' => $cache->getConfig()->toArray()
            ];
            $driverUsed[ $cache->getDriverName() ] = \get_class($cache);
        }

        $this->data = [
          'twigCacheBlocks' => $this->twig_cache_blocks,
          'apiVersion' => PhpfastcacheApi::getVersion(),
          'pfcVersion' => PhpfastcacheApi::getPhpFastCacheVersion(),
          'bundleVersion' => phpFastCacheBundle::VERSION,
          'apiChangelog' => PhpfastcacheApi::getChangelog(),
          'driverUsed' => $driverUsed,
          'instances' => $instances,
          'stats' => $stats,
          'size' => $size,
          'hits' => [
            'read' => (int) CacheManager::$ReadHits,
            'write' => (int) CacheManager::$WriteHits,
          ],
          'coreConfig' => [
            'driverList' => CacheManager::getDriverList(true),
            'namespacePath (deprecated)' => CacheManager::getNamespacePath(),
          ],
          'projectConfig' => [
            'twig_driver' => $this->phpfastcache->getConfig()['twig_driver'],
            'twig_block_debug' => $this->phpfastcache->getConfig()['twig_block_debug'],
          ],
        ];
    }

    /**
     * @return array
     */
    public function getStats(): array
    {
        return $this->data[ 'stats' ] ?? [];
    }

    /**
     * @return array
     */
    public function getInstances(): array
    {
        return $this->data[ 'instances' ];
    }

    /**
     * @return array
     */
    public function getDriverUsed(): array
    {
        return $this->data[ 'driverUsed' ] ?? [];
    }

    /**
     * @return array
     */
    public function getHits(): array
    {
        return $this->data[ 'hits' ] ?? [];
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->data[ 'size' ] ?? 0;
    }

    /**
     * @return array
     */
    public function getCoreConfig(): array
    {
        return $this->data[ 'coreConfig' ] ?? [];
    }

    /**
     * @return array
     */
    public function getProjectConfig(): array
    {
        return $this->data[ 'projectConfig' ] ?? [];
    }

    /**
     * @return string
     */
    public function getApiVersion(): string
    {
        return $this->data[ 'apiVersion' ];
    }

    /**
     * @return string
     */
    public function getPfcVersion(): string
    {
        return $this->data[ 'pfcVersion' ];
    }

    /**
     * @return string
     */
    public function getBundleVersion(): string
    {
        return $this->data[ 'bundleVersion' ];
    }

    /**
     * @return string
     */
    public function getApiChangelog(): string
    {
        return $this->data[ 'apiChangelog' ] ?? '';
    }

    /**
     * @param string $blockName
     * @param array $cacheBlock
     * @return $this
     */
    public function setTwigCacheBlock($blockName, array $cacheBlock): self
    {
        if(isset($this->twig_cache_blocks[$blockName])){
            $this->twig_cache_blocks[$blockName] = \array_merge($this->twig_cache_blocks[$blockName], $cacheBlock);
        }else{
            $this->twig_cache_blocks[$blockName] = $cacheBlock;
        }


        return $this;
    }

    /**
     * @return array
     */
    public function getTwigCacheBlocks(): array
    {
        return $this->data[ 'twigCacheBlocks' ] ?? [];
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'phpfastcache';
    }

    /**
     * @inheritdoc
     */
    public function reset()
    {
        $this->data = [];
        $this->twig_cache_blocks = [];
    }
}