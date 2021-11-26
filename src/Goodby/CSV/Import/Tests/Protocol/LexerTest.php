<?php

namespace Goodby\CSV\Import\Tests\Protocol;

use Goodby\CSV\Import\Protocol\Exception\CsvFileNotFoundException;
use Goodby\CSV\Import\Protocol\InterpreterInterface;
use Goodby\CSV\Import\Protocol\LexerInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery as m;
use PHPUnit\Framework\TestCase;

/**
 * unit test for CSV Lexer.
 */
class LexerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testInterface()
    {
        $lexer = m::mock(LexerInterface::class);
        $interpreter = m::mock(InterpreterInterface::class);

        $path = 'dummy.csv';

        $lexer->shouldReceive('parse')->with($path, $interpreter);

        $lexer->parse($path, $interpreter);
    }

    public function testCsvFileNotFound()
    {
        $this->expectException(CsvFileNotFoundException::class);
        $lexer = m::mock(LexerInterface::class);
        $interpreter = m::mock(InterpreterInterface::class);

        $path = 'invalid_dummy.csv';

        $lexer->shouldReceive('parse')
            ->with($path, $interpreter)
            ->andThrow(CsvFileNotFoundException::class)
        ;

        $lexer->parse($path, $interpreter);
    }
}
