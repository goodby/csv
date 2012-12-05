<?php

namespace Goodby\CSV\Import\Standard;

use Goodby\CSV\Import\Protocol\LexerInterface;
use Goodby\CSV\Import\Protocol\InterpreterInterface;
use Goodby\CSV\Import\Protocol\Exception\CsvFileNotFoundException;

class Lexer implements LexerInterface
{
    /**
     * @param string               $path
     * @param InterpreterInterface $interpreter
     * @return boolean
     * @throws CsvFileNotFoundException
     */
    public function parse($path, InterpreterInterface $interpreter)
    {
        // TODO: Implement parse() method.
    }
}
