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

declare(strict_types=1);

namespace Phpfastcache\Bundle\Tests\Functional;

use Phpfastcache\CacheManager as PhpfastcacheManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class IntegrationTest
 * @package Phpfastcache\Bundle\Tests\Functional
 * @runInSeparateProcesses
 */
class MainFunctionalTest extends AbstractWebTestCase
{
    public function testCacheMiss()
    {
        $response = $this->profileRequest('GET', '/cache-test');

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('miss', \json_decode($response->getContent(), true)[ 'cache' ]);
    }

    public function testCacheHit()
    {
        $response = $this->profileRequest('GET', '/cache-test');

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('hit', \json_decode($response->getContent(), true)[ 'cache' ]);
    }

    public function testCacheError()
    {
        $response = $this->profileRequest('GET', '/cache-error');

        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    public function testCacheHttp()
    {
        /**
         * The first request should be a MISS one
         */
        $response = $this->profileRequest('GET', '/cache-http');
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $etag = $response->getEtag();

        /**
         * Clear instance to avoid Phpfastcache alerts
         * as we are in a shared process context
         */
        PhpfastcacheManager::clearInstances();

        /**
         * The second request should be a HIT one with a 304 response
         */
        $response = $this->profileRequest('GET', '/cache-http', [
          'If-None-Match' => $etag,
        ]);
        $this->assertSame(Response::HTTP_NOT_MODIFIED, $response->getStatusCode());
        $this->assertSame('', trim($response->getContent()));
    }
}