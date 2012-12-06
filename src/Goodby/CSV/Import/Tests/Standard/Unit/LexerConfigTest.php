<?php

namespace Goodby\CSV\Import\Tests\Standard\Unit;

use Goodby\CSV\Import\Standard\LexerConfig;

class LexerConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testDelimiter()
    {
        $config = new LexerConfig();
        $this->assertSame(',', $config->getDelimiter());
        $config->setDelimiter('del');
        $this->assertSame('del', $config->getDelimiter());
    }

    public function testEnclosure()
    {
        $config = new LexerConfig();
        $this->assertSame('"', $config->getEnclosure());
        $this->assertSame('enc', $config->setEnclosure('enc')->getEnclosure());
    }

    public function testEscape()
    {
        $config = new LexerConfig();
        $this->assertSame('\\', $config->getEscape());
        $this->assertSame('esc', $config->setEscape('esc')->getEscape());
    }

    public function testFromCharset()
    {
        $config = new LexerConfig();
        $this->assertSame(null, $config->getFromCharset());
        $this->assertSame('UTF-8', $config->setFromCharset('UTF-8')->getFromCharset());
    }

    public function testToCharset()
    {
        $config = new LexerConfig();
        $this->assertSame(null, $config->getToCharset());
        $this->assertSame('UTF-8', $config->setToCharset('UTF-8')->getToCharset());
    }
}
