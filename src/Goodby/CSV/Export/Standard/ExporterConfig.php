<?php

namespace Goodby\CSV\Export\Standard;

/**
 * Config for Exporter object
 */
class ExporterConfig
{
    /**
     * Delimiter
     * @var string
     */
    private $delimiter = ',';

    /**
     * Enclosure
     * @var string
     */
    private $enclosure = '"';

    /**
     * Escape
     * @var string
     */
    private $escape = '\\';

    /**
     * Newline code
     * @var string
     */
    private $newline = "\r\n";

    /**
     * Set delimiter
     * @param string $delimiter
     * @return ExporterConfig
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * Return delimiter
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * Set enclosure
     * @param string $enclosure
     * @return ExporterConfig
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
     * Return enclosure
     * @return string
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * Set escape
     * @param string $escape
     * @return ExporterConfig
     */
    public function setEscape($escape)
    {
        $this->escape = $escape;
        return $this;
    }

    /**
     * Return escape
     * @return string
     */
    public function getEscape()
    {
        return $this->escape;
    }

    /**
     * Set newline
     * @param string $newline
     * @return ExporterConfig
     */
    public function setNewline($newline)
    {
        $this->newline = $newline;
        return $this;
    }

    /**
     * Return newline
     * @return string
     */
    public function getNewline()
    {
        return $this->newline;
    }
}
