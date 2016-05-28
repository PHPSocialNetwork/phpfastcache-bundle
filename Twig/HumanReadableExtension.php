<?php

namespace phpFastCache\Bundle\Twig;

/**
 * Class HumanReadableExtension
 * @package phpFastCache\Bundle\Twig
 */
class HumanReadableExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_Filter('sizeFormat', [$this, 'size_format']),
        );
    }
    /**
     * @param int $bytes Bytes/Octets
     * @param int $decimals Number for decimals to return
     * @param bool $octetFormat Use Octet notation instead of Bytes
     *
     * @return string
     */
    public function size_format($bytes, $decimals = 2, $octetFormat = false)
    {
        $bytes = (int) $bytes;
        $sz     = 'BKMGTP';
        $factor = floor(( strlen($bytes) - 1 ) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)).@$sz[ $factor ].( $factor ? ($octetFormat ? 'O' : 'B') : '' );
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