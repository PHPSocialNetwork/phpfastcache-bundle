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
 * Cache provider interface.
 *
 * @author Alexander <iam.asm89@gmail.com>
 */
interface CacheProviderInterface
{
    /**
     * @param string $key
     *
     * @return mixed False, if there was no value to be fetched. Null or a string otherwise.
     */
    public function fetch($key);

    /**
     * @param string  $key
     * @param string  $value
     * @param integer $lifetime
     *
     * @return boolean
     */
    public function save($key, $value, $lifetime = 0);
}
