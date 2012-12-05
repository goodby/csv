<?php

namespace Goodby\CSV\Import\Standard;

use Goodby\CSV\Import\Protocol\LexerInterface;
use Goodby\CSV\Import\Protocol\InterpreterInterface;
use Goodby\CSV\Import\Protocol\Exception\CsvFileNotFoundException;
use Goodby\CSV\Import\Standard\Config;

class Lexer implements LexerInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Return new Lexer object
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * {@inherit}
     */
    public function parse($filename, InterpreterInterface $interpreter)
    {
        // TODO: Implement parse() method.
    }
}
