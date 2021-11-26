<?php

namespace Goodby\CSV\Export\Tests\Standard\Join;

use Goodby\CSV\Export\Standard\Collection\CallbackCollection;
use Goodby\CSV\Export\Standard\Collection\PdoCollection;
use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;
use Goodby\CSV\TestHelper\DbManager;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class UsageTest extends TestCase
{
    /**
     * @var DbManager
     */
    private $manager = null;

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    public function setUp()
    {
        $this->root = vfsStream::setup('output');

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

        static::assertFileNotExists('vfs://output/data.csv');

        $collection = new PdoCollection($stmt);

        $config = new ExporterConfig();
        $exporter = new Exporter($config);
        $exporter->export('vfs://output/data.csv', $collection);

        $expectedContents = "1,name\r\n";
        $expectedContents .= "2,name\r\n";
        $expectedContents .= "3,name\r\n";

        static::assertSame($expectedContents, \file_get_contents('vfs://output/data.csv'));
    }

    public function testUsageWithCallbackCollection()
    {
        static::assertFileNotExists('vfs://output/data.csv');

        $data = [];
        $data[] = [1, 'name1'];
        $data[] = [2, 'name2'];
        $data[] = [3, 'name3'];

        $collection = new CallbackCollection($data, function($row) {
            $row[1] = $row[1] . '!';

            return $row;
        });

        $config = new ExporterConfig();
        $exporter = new Exporter($config);
        $exporter->export('vfs://output/data.csv', $collection);

        $expectedContents = "1,name1!\r\n";
        $expectedContents .= "2,name2!\r\n";
        $expectedContents .= "3,name3!\r\n";

        static::assertSame($expectedContents, \file_get_contents('vfs://output/data.csv'));
    }
}
