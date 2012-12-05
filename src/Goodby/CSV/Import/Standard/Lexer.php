<?php

namespace Goodby\CSV\Import\Standard;

use Goodby\CSV\Import\Protocol\LexerInterface;
use Goodby\CSV\Import\Protocol\InterpreterInterface;
use Goodby\CSV\Import\Protocol\Exception\CsvFileNotFoundException;
use Goodby\CSV\Import\Standard\Config;
use Goodby\CSV\Import\Standard\StreamFilter\ConvertMbstringEncoding;
use SplFileObject;

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
        ConvertMbstringEncoding::register();
    }

    /**
     * {@inherit}
     */
    public function parse($filename, InterpreterInterface $interpreter)
    {
        ini_set('auto_detect_line_endings', true); // For mac's office excel csv

        $url = ConvertMbstringEncoding::getFilterURL($filename, $this->config->getFromCharset(), $this->config->getToCharset());
        $csv = new SplFileObject($url);
        $csv->setCsvControl($this->config->getDelimiter(), $this->config->getEnclosure(), $this->config->getEscape());
        $csv->setFlags(SplFileObject::READ_CSV);

        $originalLocale = setlocale(LC_ALL, '0'); // Backup current locale
        setlocale(LC_ALL, 'en_US.UTF-8');

        foreach ( $csv as $line ) {
            $interpreter->interpret($line);
        }

        setlocale(LC_ALL, $originalLocale); // Reset locale
    }
}
