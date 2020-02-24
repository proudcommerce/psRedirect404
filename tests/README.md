Run PHPUnit
===========

1. OXID eShop(>=v6.2.4) install with full setup and database.
2. Remove OXID Testing-Library (^v5), because it has a very old phpunit version (v4).
    `composer remove oxid-esales/testing-library --no-scripts`
3. Install PHPunit >v6 
    `composer require --dev phpunit/phpunit:^6 --no-scripts`
4. Run `./source/modules/pc/redirect404/run_module_tests.sh`

System requirements
-------------------

*. OXID eShop Version >CE-v6.2.4
*. PHPunit >v6


