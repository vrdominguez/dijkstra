# vrdominguez/dijkstra

Library version of the implementation in the repo [vrdominguez/dijkstra-php-without-composer-or-libraries](https://github.com/vrdominguez/dijkstra-php-without-composer-or-libraries).

Read the documentation in the [vrdominguez/dijkstra-php-without-composer-or-libraries/README.md](https://github.com/vrdominguez/dijkstra-php-without-composer-or-libraries/blob/main/README.md) for mor information about requirements.

## Changes from the original repository:

- Added composer dependencies and autoload.
- Added PHPUnit for tests.

## Install dependencies

Within the project root run the command:

```bash
composer install
```

**NOTE:** composer must be installed and with execution permissions.

## Run tests

Within the proyect root run the command:

```bash
vendor/bin/phpunit tests
```

Expected output:

```
PHPUnit 9.5.10 by Sebastian Bergmann and contributors.

.......                                                             7 / 7 (100%)

Time: 00:00.009, Memory: 6.00 MB

OK (7 tests, 62 assertions)
```