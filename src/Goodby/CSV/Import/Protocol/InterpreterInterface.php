<?php

namespace Goodby\CSV\Import\Protocol;

/**
 * Interface of the Interpreter
 *
 * @author suin
 */
interface InterpreterInterface
{
    /**
     * @param $line
     * @return void
     */
    public function interpret($line);
}
