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

namespace Phpfastcache\Bundle\Tests\Functional\App\Controller;

use Phpfastcache\Bundle\Response\CacheableResponse;
use Phpfastcache\Bundle\Service\Phpfastcache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CacheController
 * @package Phpfastcache\Bundle\Tests\Functional\App\Controller
 */
class CacheController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Phpfastcache\Bundle\Service\Phpfastcache $phpfastcache
     * @return Response
     */
    public function index(Request $request, Phpfastcache $phpfastcache)
    {
        return JsonResponse::create(['result' => 'ok']);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Phpfastcache\Bundle\Service\Phpfastcache $phpfastcache
     * @return Response
     */
    public function cacheTest(Request $request, Phpfastcache $phpfastcache)
    {
        $filecache = $phpfastcache->get('filecache');
        $cacheItem = $filecache->getItem(__FUNCTION__);
        $cacheHit = $cacheItem->isHit();

        if(!$cacheHit){
            $cacheItem->set(1337)->expiresAfter(5);
            $filecache->save($cacheItem);
        }

        return JsonResponse::create([
          'result' => 'ok',
          'cache' => $cacheHit ? 'hit' : 'miss'
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Phpfastcache\Bundle\Service\Phpfastcache $phpfastcache
     * @return Response
     */
    public function cacheHttp(Request $request, Phpfastcache $phpfastcache)
    {
        return (new CacheableResponse($phpfastcache->get('filecache'), $request))->getResponse(__FUNCTION__, 10, function () {
            return JsonResponse::create(['result' => 'ok']);
        });
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Phpfastcache\Bundle\Service\Phpfastcache $phpfastcache
     * @return Response
     */
    public function cacheError(Request $request, Phpfastcache $phpfastcache)
    {
        $phpfastcache->get('unexisting');
    }
}