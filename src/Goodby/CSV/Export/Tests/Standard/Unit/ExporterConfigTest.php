<?php

namespace Goodby\CSV\Export\Tests\Standard\Unit;

use Goodby\CSV\Export\Standard\ExporterConfig;
use PHPUnit\Framework\TestCase;

class ExporterConfigTest extends TestCase
{
    public function testDelimiter()
    {
        $config = new ExporterConfig();
        static::assertSame(',', $config->getDelimiter());
        static::assertSame('del', $config->setDelimiter('del')->getDelimiter());
    }

    public function testEnclosure()
    {
        $config = new ExporterConfig();
        static::assertSame('"', $config->getEnclosure());
        static::assertSame('enc', $config->setEnclosure('enc')->getEnclosure());
    }

    public function testEscape()
    {
        $config = new ExporterConfig();
        static::assertSame('\\', $config->getEscape());
        static::assertSame('esc', $config->setEscape('esc')->getEscape());
    }

    public function testNewline()
    {
        $config = new ExporterConfig();
        static::assertSame("\r\n", $config->getNewline());
        static::assertSame("\r", $config->setNewline("\r")->getNewline());
    }

    public function testFromCharset()
    {
        $config = new ExporterConfig();
        static::assertSame('auto', $config->getFromCharset());
        static::assertSame('UTF-8', $config->setFromCharset('UTF-8')->getFromCharset());
    }

    public function testToCharset()
    {
        $config = new ExporterConfig();
        static::assertSame(null, $config->getToCharset());
        static::assertSame('UTF-8', $config->setToCharset('UTF-8')->getToCharset());
    }

    public function testColumnHeaders()
    {
        $columnHeaders = [
            'Header 1',
            'Header 2',
            'Header 3',
        ];

        $config = new ExporterConfig();
        static::assertSame([], $config->getColumnHeaders());
        static::assertSame($columnHeaders, $config->setColumnHeaders($columnHeaders)->getColumnHeaders());
    }
}
