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
                path: %kernel.cache_dir%/phpfastcache/
```
* More examples in Docs/Example/app/config

##### :wrench: Setup your AppKernel.php by adding the phpFastCache Bundle

```php
$bundles[] = new phpFastCache\Bundle\phpFastCacheBundle();
```

* See the file Docs/Example/app/AppKernel.php for more information.

#### :rocket: Accelerate your app by making use of PhpFastCache service

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

