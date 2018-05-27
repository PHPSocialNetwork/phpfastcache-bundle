<?php

namespace AppBundle\Controller;

use Phpfastcache\Bundle\Service\Phpfastcache;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Phpfastcache\Exceptions\PhpfastcacheDriverCheckException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, Phpfastcache $phpfastcache)
    {
        $pfc_test = null;

        $cache = $phpfastcache->get('filecache');
        $cache2 = $phpfastcache->get('memcachecache');
        $cache3 = $phpfastcache->get('ssdbcache');
        $cache4 = $phpfastcache->get('sqlitecache');
        $cache5 = $phpfastcache->get('rediscache');
        $cache6 = $phpfastcache->get('mongodbcache');
        $cache7 = $phpfastcache->get('couchbasecache');
        $cache8 = $phpfastcache->get('leveldbcache');

        /**
         * Xcache and APC cannot coexists
         */
        try{
            $cache9 = $phpfastcache->get('apccache');
            $cache10 = $phpfastcache->get('apcucache');
        }catch(PhpfastcacheDriverCheckException $e){
            $cache11 = $phpfastcache->get('xcachecache');
        }

        $cache12 = $phpfastcache->get('devnullcache');


        $item = $cache->getItem('test');
        $item1 = $cache->getItem('test2');
        $item2 = $cache2->getItem('test');
        $item3 = $cache3->getItem('test');
        $item4 = $cache4->getItem('test');
        $item5 = $cache5->getItem('test');
        $item6 = $cache6->getItem('test');
        $item7 = $cache7->getItem('test');
        $item8 = $cache7->getItem('test2');
        $item9 = $cache8->getItem('test2');

        if(isset($cache9) && isset($cache10))
        {
            $item10 = $cache9->getItem('test2');
            $item11 = $cache10->getItem('test2');
        }

        if(isset($cache11))
        {
            $item12 = $cache11->getItem('test2');
        }

        $item13 = $cache12->getItem('test2');



        if ($item->isHit()) {
            $pfc_test = $item->get();
        } else {

            $item->set('Loaded from cache')->expiresAfter(10);
            $item1->set('Loaded from cache2')->expiresAfter(10);
            $item2->set('Loaded from cache +' . str_repeat('*', rand(1000, 5000)))->expiresAfter(10);
            $item3->set('Loaded from cache +' . str_repeat('+', rand(1000, 5000)))->expiresAfter(10);
            $item4->set('Loaded from cache +' . str_repeat('-', rand(1000, 5000)))->expiresAfter(10);
            $item5->set('Loaded from cache +' . str_repeat('-', rand(1000, 5000)))->expiresAfter(10);
            $item6->set('Loaded from cache +' . str_repeat('-', rand(1000, 5000)))->expiresAfter(10);
            $item7->set('Loaded from cache +' . str_repeat('-', rand(1000, 5000)))->expiresAfter(10);
            $item8->set('Loaded from cache2')->expiresAfter(10);
            $item9->set('Loaded from cache +' . str_repeat('-', rand(1000, 5000)))->expiresAfter(10);

            if(isset($item10) && isset($item11))
            {
                $item10->set('Loaded from cache +' . str_repeat('-', rand(1000, 5000)))->expiresAfter(10);
                $item11->set('Loaded from cache +' . str_repeat('-', rand(1000, 5000)))->expiresAfter(10);
            }

            if(isset($item12))
            {
                $item12->set('Loaded from cache +' . str_repeat('-', rand(1000, 5000)))->expiresAfter(10);
            }

            $item13->set('Loaded from cache +' . str_repeat('-', rand(1000, 5000)))->expiresAfter(10);


            $cache->save($item);
            $cache->save($item1);
            $cache2->save($item2);
            $cache3->save($item3);
            $cache4->save($item4);
            $cache5->save($item5);
            $cache6->save($item6);
            $cache7->save($item7);
            $cache7->save($item8);
            $cache8->save($item9);

            if(isset($cache9) && isset($cache10))
            {
                $cache9->save($item10);
                $cache10->save($item11);
            }

            if(isset($cache11))
            {
                $cache11->save($item12);
            }

            $cache12->save($item13);


            $pfc_test = 'Not loaded from cache';
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
          'pfc_test' => $pfc_test,
          'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }
}