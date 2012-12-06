<?php

namespace Goodby\CSV\Export\Standard;

use Goodby\CSV\Export\Protocol\ExporterInterface;
use Goodby\CSV\Export\Protocol\Exception\IOException;
use Goodby\CSV\Export\Standard\ExporterConfig;
use SplFileObject;

/**
 * Standard exporter class
 */
class Exporter implements ExporterInterface
{
    /**
     * @var ExporterConfig
     */
    private $config;

    /**
     * Return new Exporter object
     * @param ExporterConfig $config
     */
    public function __construct(ExporterConfig $config)
    {
        $this->config = $config;
    }

    /**
     * {@inherit}
     */
    public function export($filename, $rows)
    {
        $pointer = fopen($filename, 'w+');

        $delimiter = $this->config->getDelimiter();
        $enclosure = $this->config->getEnclosure();
        $newline   = $this->config->getNewline();

        foreach ( $rows as $row ) {
            fputcsv($pointer, $row, $delimiter, $enclosure);
            $this->_replaceNewline($pointer, $newline);
        }

        fclose($pointer);
    }

    /**
     * Replace new line character
     * @param resource $pointer
     * @param string $newline
     */
    private function _replaceNewline($pointer, $newline)
    {
        /**
         * Because php_fputcsv() implementation in PHP source code
         * has hardcoded "\n", this method seek one character back
         * and replace newline code with what client code wish.
         */
        fseek($pointer, ftell($pointer) - 1);
        fputs($pointer, $newline);
    }
}
