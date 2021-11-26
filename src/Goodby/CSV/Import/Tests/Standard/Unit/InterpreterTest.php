<?php

namespace Goodby\CSV\Import\Tests\Standard\Unit;

use Goodby\CSV\Import\Protocol\Exception\InvalidLexicalException;
use Goodby\CSV\Import\Standard\Exception\StrictViolationException;
use Goodby\CSV\Import\Standard\Interpreter;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery as m;
use PHPUnit\Framework\TestCase;

/**
 * unit test for Standard Implementation of the Interpreter.
 */
class InterpreterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private $expectedLine;

    public function setUp()
    {
        $this->expectedLine = null;
    }

    /**
     * @requires PHP 5.4
     */
    public function testStandardInterpreterWithClosure()
    {
        $this->expectedLine = ['test', 'test', 'test'];

        $interpreter = new Interpreter();
        $interpreter->addObserver(function($line) {
            static::assertEquals($this->expectedLine, $line);
        });

        $interpreter->interpret($this->expectedLine);
    }

    public function testStandardInterpreterWithObject()
    {
        $this->expectedLine = ['test', 'test', 'test'];

        $object = m::mock(\stdClass::class);
        $object->shouldReceive('callback')->with($this->expectedLine)->once();

        $interpreter = new Interpreter();
        $interpreter->addObserver([$object, 'callback']);

        $interpreter->interpret($this->expectedLine);
    }

    public function testInconsistentColumns()
    {
        $this->expectException(StrictViolationException::class);
        $lines = [];
        $lines[] = ['test', 'test', 'test'];
        $lines[] = ['test', 'test'];

        $interpreter = new Interpreter();

        foreach ($lines as $line) {
            $interpreter->interpret($line);
        }
    }

    public function testInconsistentColumnsLowToHigh()
    {
        $this->expectException(StrictViolationException::class);
        $lines = [];
        $lines[] = ['test', 'test'];
        $lines[] = ['test', 'test', 'test'];

        $interpreter = new Interpreter();

        foreach ($lines as $line) {
            $interpreter->interpret($line);
        }
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testConsistentColumns()
    {
        $lines = [];
        $lines[] = ['test', 'test', 'test'];
        $lines[] = ['test', 'test', 'test'];

        $interpreter = new Interpreter();

        foreach ($lines as $line) {
            $interpreter->interpret($line);
        }
    }

    /**
     * use un-strict won't throw exception with inconsistent columns.
     *
     * @doesNotPerformAssertions
     */
    public function testInconsistentColumnsWithUnStrict()
    {
        $lines = [];
        $lines[] = ['test', 'test', 'test'];
        $lines[] = ['test', 'test'];

        $interpreter = new Interpreter();
        $interpreter->unstrict();

        foreach ($lines as $line) {
            $interpreter->interpret($line);
        }
    }

    public function testStandardInterpreterWithInvalidLexical()
    {
        $this->expectException(InvalidLexicalException::class);
        $this->expectedLine = '';

        $interpreter = new Interpreter();

        $interpreter->interpret($this->expectedLine);
    }

    public function testInvalidCallable()
    {
        $this->expectException(\InvalidArgumentException::class);
        $interpreter = new Interpreter();

        $interpreter->addObserver('dummy');

        $interpreter->interpret($this->expectedLine);
    }
}
