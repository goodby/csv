<?php

namespace Goodby\CSV\Export\Standard;

use Goodby\CSV\Export\Protocol\ExporterInterface;
use Goodby\CSV\Export\Protocol\Exception\IOException;
use Goodby\CSV\Export\Standard\ExporterConfig;
use Goodby\CSV\Export\Standard\Exception\StrictViolationException;

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
     * @var int
     */
    private $rowConsistency = null;

    /**
     * @var bool
     */
    private $strict = true;

    /**
     * Return new Exporter object
     * @param ExporterConfig $config
     */
    public function __construct(ExporterConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Disable strict mode
     */
    public function unstrict()
    {
        $this->strict = false;
    }

    /**
     * {@inherit}
     * @throws StrictViolationException
     */
    public function export($filename, $rows)
    {
        $pointer = @fopen($filename, 'w+');

        if ( $pointer === false ) {
            $lastError = error_get_last();
            throw new IOException($lastError['message']);
        }

        $delimiter   = $this->config->getDelimiter();
        $enclosure   = $this->config->getEnclosure();
        $newline     = $this->config->getNewline();
        $fromCharset = $this->config->getFromCharset();
        $toCharset   = $this->config->getToCharset();

        foreach ( $rows as $row ) {
            $this->checkRowConsistency($row);
            $row = $this->convertEncoding($row, $toCharset, $fromCharset);
            fputcsv($pointer, $row, $delimiter, $enclosure);
            $this->replaceNewline($pointer, $newline);
        }

        fclose($pointer);
    }

    /**
     * Replace new line character
     * @param resource $pointer
     * @param string $newline
     */
    private function replaceNewline($pointer, $newline)
    {
        /**
         * Because php_fputcsv() implementation in PHP source code
         * has hardcoded "\n", this method seek one character back
         * and replace newline code with what client code wish.
         */
        $result = @fseek($pointer, ftell($pointer) - 1);

        if ( $result === -1 ) {
            return; // case: php://output, php://stdout and so on
        }

        fputs($pointer, $newline);
    }

    /**
     * Check if the column count is consistent with comparing other rows
     * @param array|\Countable $row
     * @throws Exception\StrictViolationException
     */
    private function checkRowConsistency($row)
    {
        if ( $this->strict === false ) {
            return;
        }

        $current = count($row);

        if ( $this->rowConsistency === null ) {
            $this->rowConsistency = $current;
        }

        if ( $current !== $this->rowConsistency ) {
            throw new StrictViolationException();
        }

        $this->rowConsistency = $current;
    }

    /**
     * @param array $columns
     * @param string $to
     * @param string $from
     * @return array
     */
    private function convertEncoding($columns, $to, $from)
    {
        if ( $to === null ) {
            return $columns;
        }

        return array_map(function($column) use($to, $from) {
            return mb_convert_encoding($column, $to, $from);
        }, $columns);
    }
}
