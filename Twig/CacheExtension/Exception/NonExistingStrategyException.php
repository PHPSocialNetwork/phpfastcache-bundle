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

namespace phpFastCache\Bundle\Twig\CacheExtension\Exception;

class NonExistingStrategyException extends BaseException
{
    /**
     * {@inheritdoc}
     */
    public function __construct($strategyKey, $code = 0, \Exception $previous = null)
    {
        parent::__construct(sprintf('No strategy configured with key "%s".', $strategyKey), $code, $previous);
    }
}
