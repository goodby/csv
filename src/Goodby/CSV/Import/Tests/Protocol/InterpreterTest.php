<?php

namespace Goodby\CSV\Import\Tests\Protocol;

use Mockery as m;
use Goodby\CSV\Import\Protocol\Exception\InvalidLexicalException;

/**
 * unit test for Interface of the Interpreter
 */
class InterpreterTest extends \PHPUnit_Framework_TestCase
{
    public function testInterpreterInterface()
    {
        $interpreter = m::mock('\Goodby\CSV\Import\Protocol\InterpreterInterface');

        $line = array();

        $interpreter->shouldReceive('interpret')->with($line);

        $interpreter->interpret($line);
    }

    /**
     * @expectedException \Goodby\CSV\Import\Protocol\Exception\InvalidLexicalException
     */
    public function testInterpreterInterfaceWillThrownInvalidLexicalException()
    {
        $interpreter = m::mock('\Goodby\CSV\Import\Protocol\InterpreterInterface');

        $line = "INVALID LEXICAL";

        new InvalidLexicalException();

        $interpreter->shouldReceive('interpret')
                    ->with($line)
                    ->andThrow('\Goodby\CSV\Import\Protocol\Exception\InvalidLexicalException');

        $interpreter->interpret($line);
    }
}
