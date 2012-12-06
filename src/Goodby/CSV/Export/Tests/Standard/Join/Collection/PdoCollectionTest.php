<?php

namespace Goodby\CSV\Export\Tests\Standard\Join\Collection;

use Goodby\CSV\Export\Standard\Collection\PdoCollection;
use Goodby\CSV\TestHelper\DbManager;

class PdoCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Goodby\CSV\TestHelper\DbManager
     */
    private $manager = null;

    public function setUp()
    {
        $this->manager = new DbManager();

        $pdo = $this->manager->getPdo();

        $stmt = $pdo->prepare("CREATE TABLE collection_test ( id INT, name VARCHAR(32) )");
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

        $stmt = $pdo->prepare("SELECT * FROM collection_test");
        $stmt->execute();

        $pdoCollection = new PdoCollection($stmt);

        foreach ($pdoCollection as $line) {
            $this->assertEquals("name", $line["name"]);
        }
    }
}