Because the V3 is not backward compatible with the V2 we will help you to migrate your code:


### Configuration name:

#### :clock1: Then:
```yaml
# PhpFastCache configuration
php_fast_cache:
    twig_driver: "filecache" # This option must be a valid declared driver, in our example: "filecache"
    twig_block_debug: true # This option will wrap CACHE/ENDCACHE blocks with block debug as HTML comment
    drivers:
        filecache:
            type: Files
            parameters:
                path: "%kernel.cache_dir%/phpfastcache/"
```

#### :alarm_clock: Now:

```yaml
# PhpFastCache configuration
phpfastcache:
    twig_driver: "filecache" # This option must be a valid declared driver, in our example: "filecache"
    twig_block_debug: true # This option will wrap CACHE/ENDCACHE blocks with block debug as HTML comment
    drivers:
        filecache:
            type: Files
            parameters:
                path: "%kernel.cache_dir%/phpfastcache/"
```
Notice the change from "php_fast_cache" to "phpfastcache".

### Service container

We used to call the container getter to retrieve the "phpfastcache" service.
#### :clock1: Then:
```php
<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $cache = $this->get('phpfastcache')->get('filecache');
        // ...
    }
}
```

#### :alarm_clock: Now:
This is no longer possible since the service is now private and can be retrieved via the dependency injection 
using the Symfony [autowire](https://symfony.com/doc/current/service_container/autowiring.html) feature.
```php
<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Phpfastcache\Bundle\Service\Phpfastcache;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, Phpfastcache $phpfastcache)
    {
        $cache = $phpfastcache->get('filecache');
        // ...
    }
}
```

