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

### Memory Management Free

This library designed for memory unbreakable.
every each line processsing line by line.

It will not be accumulated in the memory whole rows.

### Import to Database via PDO

```php
<?php

use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;

$pdo = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');
$pdo->query('CREATE TABLE IF NOT EXISTS user (id INT, `name` VARCHAR(255), email VARCHAR(255))');

$config = new LexerConfig();
$lexer = new Lexer($config);

$interpreter = new Interpreter();

$interpreter->addObserver(function(array $columns) use ($pdo) {
    $stmt = $pdo->prepare('INSERT INTO user (id, name, email) VALUES (?, ?, ?)');
    $stmt->execute($columns);
});

$lexer->parse('user.csv', $interpreter);

```

### Export from array

```php
<?php

use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;

$config = new ExporterConfig();
$exporter = new Exporter($config);

$exporter->export('php://output', array(
    array('1', 'alice', 'alice@example.com'),
    array('2', 'bob', 'bob@example.com'),
    array('3', 'carol', 'carol@example.com'),
));
```


### Export from PDO

```php
<?php


use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;

use Goodby\CSV\Export\Standard\Collection\PdoCollection;

$pdo = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');

$pdo->query('CREATE TABLE IF NOT EXISTS user (id INT, `name` VARCHAR(255), email VARCHAR(255))');
$pdo->prepare("INSERT INTO user VALUES(1, 'alice', 'alice@example.com')")->execute();
$pdo->prepare("INSERT INTO user VALUES(2, 'bob', 'bob@example.com')")->execute();
$pdo->prepare("INSERT INTO user VALUES(3, 'carol', 'carol@example.com')")->execute();

$config = new ExporterConfig();
$exporter = new Exporter($config);

$stmt = $pdo->prepare("SELECT * FROM user");
$stmt->execute();

$exporter->export('php://output', new PdoCollection($stmt));
```


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