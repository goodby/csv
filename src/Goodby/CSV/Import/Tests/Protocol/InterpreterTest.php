<?php

namespace Goodby\CSV\Import\Tests\Protocol;

use Goodby\CSV\Import\Protocol\Exception\InvalidLexicalException;

/**
 * unit test for Interface of the Interpreter
 */
class InterpreterTest extends \PHPUnit_Framework_TestCase
{
    public function testInterpreterInterface()
    {
        $line = array();
        $url  = 'filepath';

        $interpreter = $this->getMock('\Goodby\CSV\Import\Protocol\InterpreterInterface');

        $interpreter->expects($this->once())
            ->method('interpret')
            ->with($this->identicalTo($line))
        ;

        $interpreter->interpret($line, $url);
    }

    /**
     * @expectedException \Goodby\CSV\Import\Protocol\Exception\InvalidLexicalException
     */
    public function testInterpreterInterfaceWillThrownInvalidLexicalException()
    {
        $interpreter = $this->getMock('\Goodby\CSV\Import\Protocol\InterpreterInterface');

        $interpreter->expects($this->once())
            ->method('interpret')
            ->will($this->throwException(new InvalidLexicalException()))
        ;

        $line = "INVALID LEXICAL";
        $url  = 'filepath';

        $interpreter->interpret($line, $url);
    }
}
