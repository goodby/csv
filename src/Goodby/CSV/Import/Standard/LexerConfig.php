<?php

namespace Goodby\CSV\Import\Standard;

/**
 * Config for Lexer object
 */
class LexerConfig
{
    /**
     * @var string
     */
    private $delimiter = ',';

    /**
     * @var string
     */
    private $enclosure = '"';

    /**
     * @var string
     */
    private $escape = '\\';

    /**
     * @var string
     */
    private $fromCharset;

    /**
     * @var string
     */
    private $toCharset;

    /**
     * Set delimiter
     * @param string $delimiter
     * @return LexerConfig
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
     * @return LexerConfig
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
     * @return LexerConfig
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
     * Set from-character set
     * @param string $fromCharset
     * @return LexerConfig
     */
    public function setFromCharset($fromCharset)
    {
        $this->fromCharset = $fromCharset;
        return $this;
    }

    /**
     * Return from-character set
     * @return string
     */
    public function getFromCharset()
    {
        return $this->fromCharset;
    }

    /**
     * Set to-character set
     * @param string $toCharset
     * @return LexerConfig
     */
    public function setToCharset($toCharset)
    {
        $this->toCharset = $toCharset;
        return $this;
    }

    /**
     * Return to-character set
     * @return string
     */
    public function getToCharset()
    {
        return $this->toCharset;
    }
}
