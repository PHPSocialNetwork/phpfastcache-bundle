<?php
/**
 * Created by PhpStorm.
 * User: Geolim4
 * Date: 12/05/2018
 * Time: 02:18
 */

namespace Phpfastcache\Bundle\Response;

use Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface;
use Phpfastcache\Exceptions\PhpfastcacheLogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheableResponse
{
    const RESPONSE_PREFIX = '__CACH_RESP__';

    /**
     * @var ExtendedCacheItemPoolInterface
     */
    protected $request;

    /**
     * @var ExtendedCacheItemPoolInterface
     */
    protected $cacheInstance;

    /**
     * CachePromise constructor.
     * @param ExtendedCacheItemPoolInterface $cacheInstance
     */
    public function __construct(ExtendedCacheItemPoolInterface $cacheInstance, Request $request)
    {
        $this->cacheInstance = $cacheInstance;
        $this->request = $request;
    }

    /**
     * @param string $cacheKey
     * @param int|\DateInterval $expiresAfter
     * @param callable $callback
     * @return mixed
     * @throws PhpfastcacheLogicException
     */
    public function getResponse(string $cacheKey, $expiresAfter = null, callable $callback): Response
    {
        $cacheKey = self::RESPONSE_PREFIX . $cacheKey;
        $cacheItem = $this->cacheInstance->getItem($cacheKey);
        $cacheResponse = $cacheItem->get();

        /**
         * No isHit() test here as we directly
         * test if the cached response object
         * is effectively a "Response" object
         */
        if (!($cacheResponse instanceof Response)) {
            $response = $callback();
            if($response instanceof Response){
                $cacheItem->expiresAfter($expiresAfter);

                $response->setExpires($cacheItem->getExpirationDate());
                $response->setSharedMaxAge($cacheItem->getTtl());
                $response->headers->addCacheControlDirective('must-revalidate', true);
                $response->setEtag(md5($response->getContent()));
                $response->setPublic();

                $cacheItem->set($response);
                $this->cacheInstance->save($cacheItem);
                $cacheResponse = $response;
            }else{
               throw new PhpfastcacheLogicException('Your callback response MUST return a valid Symfony HTTP Foundation Response object');
            }
        }else{
            $cacheResponse->isNotModified($this->request);
        }

        return $cacheResponse;
    }
}