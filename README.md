[![Code Climate](https://codeclimate.com/github/PHPSocialNetwork/phpfastcache-bundle/badges/gpa.svg)](https://codeclimate.com/github/PHPSocialNetwork/phpfastcache-bundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PHPSocialNetwork/phpfastcache-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/PHPSocialNetwork/phpfastcache-bundle/?branch=master) [![Build Status](https://travis-ci.org/PHPSocialNetwork/phpfastcache-bundle.svg?branch=master)](https://travis-ci.org/PHPSocialNetwork/phpfastcache) [![Latest Stable Version](http://img.shields.io/packagist/v/phpfastcache/phpfastcache-bundle.svg)](https://packagist.org/packages/phpfastcache/phpfastcache-bundle) [![Total Downloads](http://img.shields.io/packagist/dt/phpfastcache/phpfastcache-bundle.svg)](https://packagist.org/packages/phpfastcache/phpfastcache) [![Dependency Status](https://www.versioneye.com/php/phpfastcache:phpfastcache-bundle/badge.svg)](https://www.versioneye.com/php/phpfastcache:phpfastcache-bundle) [![License](https://img.shields.io/packagist/l/phpfastcache/phpfastcache-bundle.svg)](https://packagist.org/packages/phpfastcache/phpfastcache-bundle)
# Symfony 3 PhpFastCache Bundle


#### :thumbsup: Step 1: Include phpFastCache Bundle in your project with composer:

```bash
composer require phpfastcache/phpfastcache-bundle
```

#### :construction: Step 2: Setup your config.yml to configure your cache(s) instance(s)


```yml
# PhpFastCache configuration
php_fast_cache:
    drivers:
        filecache:
            type: Files
            parameters:
                path: "%kernel.cache_dir%/phpfastcache/"
```
* More examples in Docs/Example/app/config

#### :wrench: Step 3: Setup your AppKernel.php by adding the phpFastCache Bundle

```php
$bundles[] = new phpFastCache\Bundle\phpFastCacheBundle();
```

* See the file Docs/Example/app/AppKernel.php for more information.

#### :rocket: Step 4: Accelerate your app by making use of PhpFastCache service

```php
public function indexAction(Request $request)
{
    $cache = $this->get('phpfastcache')->get('filecache');
    $item = $cache->getItem('myAppData');
    
    if (!$item->isHit() || $item->get() === null) {
        $item->set('Wy app has now superpowers !!')->expiresAfter(3600);//1 hour
        $cache->save($item);
    } 
     
    // replace this example code with whatever you need
    return $this->render('default/index.html.twig', [
        'myAppData' => $item->get(),
        'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
    ]);
}
```

#### :boom: phpFastCache Bundle support
Found an issue or had an idea ? Come here [here](https://github.com/PHPSocialNetwork/phpfastcache-bundle/issues) and let us know !

