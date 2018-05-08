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