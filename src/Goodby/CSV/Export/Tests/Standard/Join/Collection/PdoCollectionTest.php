<?php

namespace Goodby\CSV\Export\Tests\Standard\Join\Collection;

use Goodby\CSV\Export\Standard\Collection\CallbackCollection;
use Goodby\CSV\Export\Standard\Collection\PdoCollection;
use Goodby\CSV\TestHelper\DbManager;
use PHPUnit\Framework\TestCase;

class PdoCollectionTest extends TestCase
{
    /**
     * @var DbManager
     */
    private $manager = null;

    public function setUp()
    {
        $this->manager = new DbManager();

        $pdo = $this->manager->getPdo();

        $stmt = $pdo->prepare('CREATE TABLE collection_test ( id INT, name VARCHAR(32) )');
        $stmt->execute();

        $pdo->prepare("INSERT INTO collection_test VALUES(1, 'name')")->execute();
        $pdo->prepare("INSERT INTO collection_test VALUES(2, 'name')")->execute();
        $pdo->prepare("INSERT INTO collection_test VALUES(3, 'name')")->execute();
    }

    public function tearDown()
    {
        unset($this->manager);
    }

    public function testUsage()
    {
        $pdo = $this->manager->getPdo();

        $stmt = $pdo->prepare('SELECT * FROM collection_test');
        $stmt->execute();

        $pdoCollection = new PdoCollection($stmt);

        foreach ($pdoCollection as $line) {
            static::assertEquals('name', $line['name']);
        }
    }

    public function testUsageWithCallbackCollection()
    {
        $pdo = $this->manager->getPdo();

        $stmt = $pdo->prepare('SELECT * FROM collection_test');
        $stmt->execute();

        $pdoCollection = new PdoCollection($stmt);

        $callbackCollection = new CallbackCollection($pdoCollection, function($row) {
            $row['test'] = 'test';

            return $row;
        });

        foreach ($callbackCollection as $line) {
            static::assertEquals('test', $line['test']);
        }
    }
}
