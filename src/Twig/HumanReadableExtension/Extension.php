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

namespace Phpfastcache\Bundle\Twig\HumanReadableExtension;

/**
 * Class HumanReadableExtension
 * @package Phpfastcache\Bundle\Twig
 */
class Extension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
          new \Twig_SimpleFilter('sizeFormat', [$this, 'size_format']),
        ];
    }

    /**
     * @param int $bytes Bytes/Octets
     * @param int $decimals Number for decimals to return
     * @param bool $octetFormat Use Octet notation instead of Bytes
     * @param string $separator The unit separator
     *
     * @return string
     */
    public function size_format($bytes, $decimals = 2, $octetFormat = false, $separator = '')
    {
        $bytes = (int)$bytes;
        $sz = 'BKMGTP';
        $factor = floor((\strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / (1024 ** $factor)) . $separator . @$sz[ $factor ] . ($factor ? ($octetFormat ? 'O' : 'B') : '');
    }

    /**
     * Extension name
     *
     * @return string
     */
    public function getName()
    {
        return 'human_readable_extension';
    }
}