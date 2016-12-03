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
 * @author Alexander (asm89) <iam.asm89@gmail.com>
 * @author Khoa Bui (khoaofgod)  <khoaofgod@gmail.com> http://www.phpfastcache.com
 *
 */

namespace phpFastCache\Bundle\Twig\CacheExtension\CacheProvider;

use phpFastCache\Bundle\Twig\CacheExtension\CacheProviderInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Adapter class to make extension interoperable with every PSR-6 adapter.
 *
 * @see http://php-cache.readthedocs.io/
 *
 * @author Rvanlaak <rvanlaak@gmail.com>
 */
class PsrCacheAdapter implements CacheProviderInterface
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param string $key
     * @return mixed|false
     */
    public function fetch($key)
    {
        // PSR-6 implementation returns null, CacheProviderInterface expects false
        $item = $this->cache->getItem($key);
        if ($item->isHit()) {
            return $item->get();
        }
        return false;
    }

    /**
     * @param string $key
     * @param string $value
     * @param int|\DateInterval $lifetime
     * @return bool
     */
    public function save($key, $value, $lifetime = 0)
    {
        $item = $this->cache->getItem($key);
        $item->set($value);
        $item->expiresAfter($lifetime);

        return $this->cache->save($item);
    }

}
