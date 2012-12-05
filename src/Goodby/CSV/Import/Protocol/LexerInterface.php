<?php

namespace Goodby\CSV\Import\Protocol;

/**
 * Interface of Lexer
 *
 * @author suin
 */
interface LexerInterface
{
    /**
     * @param $path
     * @param InterpreterInterface $interpreter
     * @return boolean
     * @throw Goodby\CSV\Import\Protocol\Exception\CsvFileNotFoundException
     */
    public function parse($path, InterpreterInterface $interpreter);
}
