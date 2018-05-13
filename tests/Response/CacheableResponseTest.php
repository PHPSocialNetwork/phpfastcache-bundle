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
 *
 */

namespace Phpfastcache\PhpfastcacheBundle\Tests\Response;

use Phpfastcache\Bundle\Service\Phpfastcache;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CacheableResponseTest extends WebTestCase
{
    public function testServiceTypeHint()
    {
        $client = static::createClient();
        $request = $client->getRequest();
        $phpfastcache = new Phpfastcache([
          'twig_driver' => 'filecache',
          'twig_block_debug' => true,
          'drivers' =>
            [
              'filecache' =>
                [
                  'type' => 'Files',
                  'parameters' => [],
                ],
            ],
        ]);

        $cachedResponse = (new CacheableResponse($phpfastcache->get('filecache'), $request))->getResponse('my-cache-key', 10, function () {
            return new Response('test-123');
        });

        $this->assertEquals(true, true);
    }
}