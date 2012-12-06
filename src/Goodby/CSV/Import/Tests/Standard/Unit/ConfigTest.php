<?php

namespace Goodby\CSV\Import\Tests\Standard\Unit;

use Goodby\CSV\Import\Standard\LexerConfig;

class ConfigTest extends \PHPUnit_Framework_TestCase
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
}
