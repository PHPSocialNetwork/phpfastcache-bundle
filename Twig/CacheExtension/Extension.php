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

namespace phpFastCache\Bundle\Twig\CacheExtension;

/**
 * Extension for caching template blocks with twig.
 *
 * @author Alexander <iam.asm89@gmail.com>
 */
class Extension extends \Twig_Extension
{
    private $cacheStrategy;

    /**
     * @param CacheStrategyInterface $cacheStrategy
     */
    public function __construct(CacheStrategyInterface $cacheStrategy)
    {
        $this->cacheStrategy = $cacheStrategy;
    }

    /**
     * @return CacheStrategyInterface
     */
    public function getCacheStrategy()
    {
        return $this->cacheStrategy;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        if (version_compare(\Twig_Environment::VERSION, '1.26.0', '>=')) {
            return get_class($this);
        }
        return 'phpfastcache_cache';
    }

    /**
     * {@inheritDoc}
     */
    public function getTokenParsers()
    {
        return array(
            new TokenParser\Cache(),
        );
    }
}
