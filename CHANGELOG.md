## 3.0.0
##### 27 may 2018

- Added Symfony 4 (Flex) compatibility (thanks to @Pastisd)
- Updated Phpfastcache service which is now private, use Symfony [D.I](https://symfony.com/doc/current/components/dependency_injection.html) instead.
- Updated Phpfastcache dependency to the v7
- Updated namespace for more consistency and to comply with moderns standards
- Improved DataCollector interface by retrieving more information about cache
- Improved Opcache efficiency, thanks to PHP7
- Added deep tests (unit. + func.) to ensure you that the bundle is well-tested
- Added CRUD-like commands, check out [README.md](./README.md#computer-cli-command-interactions)
- Added "Cacheable Response" feature, check out [README.md](./README.md#bulb-introducing-cacheable-responses-v3-only)
- Performed global code review to remove code aberrations and simplify portions of code.
- Migration guide available [here](./src/Resources/Docs/migration/MigratingFromV2ToV3.md) to migrate from the Phpfastcache Bundle v2.

