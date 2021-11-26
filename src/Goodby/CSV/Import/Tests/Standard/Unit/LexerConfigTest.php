<?php

namespace Goodby\CSV\Import\Tests\Standard\Unit;

use Goodby\CSV\Import\Standard\LexerConfig;
use PHPUnit\Framework\TestCase;
use SplFileObject;

class LexerConfigTest extends TestCase
{
    public function testDelimiter()
    {
        $config = new LexerConfig();
        static::assertSame(',', $config->getDelimiter());
        $config->setDelimiter('del');
        static::assertSame('del', $config->getDelimiter());
    }

    public function testEnclosure()
    {
        $config = new LexerConfig();
        static::assertSame('"', $config->getEnclosure());
        static::assertSame('enc', $config->setEnclosure('enc')->getEnclosure());
    }

    public function testEscape()
    {
        $config = new LexerConfig();
        static::assertSame('\\', $config->getEscape());
        static::assertSame('esc', $config->setEscape('esc')->getEscape());
    }

    public function testFromCharset()
    {
        $config = new LexerConfig();
        static::assertSame(null, $config->getFromCharset());
        static::assertSame('UTF-8', $config->setFromCharset('UTF-8')->getFromCharset());
    }

    public function testToCharset()
    {
        $config = new LexerConfig();
        static::assertSame(null, $config->getToCharset());
        static::assertSame('UTF-8', $config->setToCharset('UTF-8')->getToCharset());
    }

    public function testFlags()
    {
        $config = new LexerConfig();
        static::assertSame(SplFileObject::READ_CSV, $config->getFlags());
        $config->setFlags(SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::READ_CSV);
        $flags = (SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::READ_CSV);
        static::assertSame($flags, $config->getFlags());
    }

    public function testIgnoreHeaderLine()
    {
        $config = new LexerConfig();
        static::assertSame(false, $config->getIgnoreHeaderLine());
        static::assertSame(true, $config->setIgnoreHeaderLine(true)->getIgnoreHeaderLine());
    }
}
