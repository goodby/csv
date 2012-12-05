<?php

namespace Goodby\CSV\Import\Protocol;

use Goodby\CSV\Import\Protocol\Exception\CsvFileNotFoundException;

/**
 * Interface of Lexer
 */
interface LexerInterface
{
    /**
     * @param string $path
     * @param InterpreterInterface $interpreter
     * @return boolean
     * @throws CsvFileNotFoundException
     */
    public function parse($path, InterpreterInterface $interpreter);
}
