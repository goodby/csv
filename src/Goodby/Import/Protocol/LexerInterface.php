<?php

namespace Goodby\Import\Protocol;

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
     */
    public function parse($path, InterpreterInterface $interpreter);
}
