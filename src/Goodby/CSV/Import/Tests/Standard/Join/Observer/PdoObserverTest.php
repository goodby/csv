<?php

namespace Goodby\CSV\Import\Tests\Standard\Join\Observer;

use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Observer\PdoObserver;
use Goodby\CSV\TestHelper\DbManager;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * unit test for pdo observer.
 */
class PdoObserverTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var DbManager
     */
    private $manager = null;

    public function setUp(): void
    {
        $this->manager = new DbManager();

        $pdo = $this->manager->getPdo();

        $stmt = $pdo->prepare('CREATE TABLE test (id INT, name VARCHAR(32), age INT, flag TINYINT, flag2 TINYINT, status VARCHAR(32), contents TEXT)');
        $stmt->execute();
    }

    public function tearDown(): void
    {
        unset($this->manager);
    }

    public function testUsage()
    {
        $interpreter = new Interpreter();

        $table = 'test';

        $dsn = $this->manager->getDsn();
        $options = ['user' => $this->manager->getUser(), 'password' => $this->manager->getPassword()];

        $sqlObserver = new PdoObserver($table, ['id', 'name', 'age', 'flag', 'flag2', 'status', 'contents'], $dsn, $options);

        $interpreter->addObserver([$sqlObserver, 'notify']);

        $interpreter->interpret(['123', 'test', '28', 'true', 'false', 'null', 'test"test']);

        $pdo = $this->manager->getPdo();

        $stmt = $pdo->prepare('SELECT * FROM ' . $table);
        $stmt->execute();

        $result = $stmt->fetch();

        static::assertEquals(123, $result[0]);
        static::assertEquals('test', $result[1]);
        static::assertEquals(28, $result[2]);
        static::assertEquals(1, $result[3]);
        static::assertEquals(0, $result[4]);
        static::assertEquals('NULL', $result[5]);
        static::assertEquals('test"test', $result[6]);
    }

    public function testInvalidLine()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('value is invalid: array');
        $interpreter = new Interpreter();

        $table = 'test';

        $options = ['user' => $this->manager->getUser(), 'password' => $this->manager->getPassword()];

        $sqlObserver = new PdoObserver($table, ['id', 'name'], $this->manager->getDsn(), $options);

        $interpreter->addObserver([$sqlObserver, 'notify']);

        $interpreter->interpret(['123', ['test', 'test']]);
    }
}
