<?php

namespace Goodby\CSV\Import\Tests\Standard\Unit;

use Goodby\CSV\Import\Standard\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testDelimiter()
    {
        $config = new Config();
        $this->assertSame(',', $config->getDelimiter());
        $config->setDelimiter('del');
        $this->assertSame('del', $config->getDelimiter());
    }

    public function testEnclosure()
    {
        $config = new Config();
        $this->assertSame('"', $config->getEnclosure());
        $this->assertSame('enc', $config->setEnclosure('enc')->getEnclosure());
    }

    public function testEscape()
    {
        $config = new Config();
        $this->assertSame('\\', $config->getEscape());
        $this->assertSame('esc', $config->setEscape('esc')->getEscape());
    }
}
