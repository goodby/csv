# Goodby, CSV

[![Build Status](https://secure.travis-ci.org/goodby/csv.png?branch=master)](https://travis-ci.org/goodby/csv)

## What is "Goodby CSV"?

Goodby CSV is a flexible and extendable open-source CSV import/export library.

```php
// Sample code... writing...
```

## Requirements

* PHP 5.3.2 or later
* mbstring

## Installation

Install composer in your project:

```
curl -s http://getcomposer.org/installer | php
```

Create a `composer.json` file in your project root:

```json
{
    "require": {
        "goodby/csv": "*"
    }
}
```

Install via composer:

```
php composer.phar install
```

## License

Csv is open-sourced software licensed under the MIT License - see the LICENSE file for details

## Documentation

editing...


## Contributing

We works under test driven development.

Checkout master source code from github:

```
hub clone goodby/csv
```

Install components via composer:

```
# If you don't have composer.phar
./scripts/bundle-devtools.sh .

# If you have composer.phar
composer.phar install --dev
```

Run phpunit:

```
./vendor/bin/phpunit
```

## Acknowledgement

editing...