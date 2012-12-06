<?php

namespace Goodby\CSV\Export\Tests\Standard\Unit;

use Goodby\CSV\Export\Standard\ExporterConfig;

class ExporterConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testDelimiter()
    {
        $config = new ExporterConfig();
        $this->assertSame(',', $config->getDelimiter());
        $this->assertSame('del', $config->setDelimiter('del')->getDelimiter());
    }

    public function testEnclosure()
    {
        $config = new ExporterConfig();
        $this->assertSame('"', $config->getEnclosure());
        $this->assertSame('enc', $config->setEnclosure('enc')->getEnclosure());
    }

    public function testEscape()
    {
        $config = new ExporterConfig();
        $this->assertSame('\\', $config->getEscape());
        $this->assertSame('esc', $config->setEscape('esc')->getEscape());
    }
}
