<?php

namespace Goodby\CSV\Import\Tests\Standard\Unit\Observer;

use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Observer\SqlObserver;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * unit test for sql observer.
 */
class SqlObserverTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testUsage()
    {
        $interpreter = new Interpreter();

        $tempDir = \sys_get_temp_dir();

        $path = $tempDir . \DIRECTORY_SEPARATOR . 'test.sql';

        if (\file_exists($path)) {
            \unlink($path);
        }

        $sqlObserver = new SqlObserver('test', ['id', 'name', 'age', 'flag', 'flag2', 'status', 'contents'], $path);

        $interpreter->addObserver([$sqlObserver, 'notify']);

        $interpreter->interpret(['123', 'test', '28', 'true', 'false', 'null', 'test"test']);

        $expectedSql = 'INSERT INTO test(id, name, age, flag, flag2, status, contents) VALUES(123, "test", 28, true, false, NULL, "test\"test");';

        static::assertEquals($expectedSql, \file_get_contents($path));
    }

    public function testInvalidLine()
    {
        $this->expectException(\InvalidArgumentException::class);
        $interpreter = new Interpreter();

        $sqlObserver = new SqlObserver('test', ['id', 'name'], 'dummy');

        $interpreter->addObserver([$sqlObserver, 'notify']);

        $interpreter->interpret(['123', ['test']]);
    }
}
