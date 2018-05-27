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

namespace Phpfastcache\Bundle\Tests\Functional\App;

use Phpfastcache\Bundle\Tests\Functional\App\Controller\CacheController;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * Class Kernel
 * @package Phpfastcache\Bundle\Tests\Functional\App
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    /**
     * @return array|iterable|\Symfony\Component\HttpKernel\Bundle\BundleInterface[]
     */
    public function registerBundles()
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Phpfastcache\Bundle\PhpfastcacheBundle(),
        ];
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param \Symfony\Component\Config\Loader\LoaderInterface $loader
     * @throws \Exception
     */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config.yaml');
    }

    /**
     * @param \Symfony\Component\Routing\RouteCollectionBuilder $routes
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->add('/', CacheController::class . ':index');
        $routes->add('/cache-test', CacheController::class . '::cacheTest');
        $routes->add('/cache-error', CacheController::class . '::cacheError');
        $routes->add('/cache-http', CacheController::class . '::cacheHttp');
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return __DIR__ . '/var';
    }

    /**
     * @return string
     */
    public function getProjectDir()
    {
        return __DIR__;
    }
}