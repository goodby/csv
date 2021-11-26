<?php

namespace Goodby\CSV\Export\Tests\Standard\Join;

use Goodby\CSV\Export\Protocol\Exception\IOException;
use Goodby\CSV\Export\Standard\Exception\StrictViolationException;
use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class ExporterTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;

    /**
     * set up test environment.
     */
    public function setUp()
    {
        $this->root = vfsStream::setup('output');
    }

    public function testExport()
    {
        $config = new ExporterConfig();
        $exporter = new Exporter($config);

        static::assertFileNotExists('vfs://output/data.csv');
        $exporter->export('vfs://output/data.csv', [
            ['ID', 'name',  'email'],
            ['1',  'alice', 'alice@example.com'],
            ['2',  'bob',   'bob@example.com'],
        ]);

        static::assertFileExists('vfs://output/data.csv');
        $expectedContents = "ID,name,email\r\n";
        $expectedContents .= "1,alice,alice@example.com\r\n";
        $expectedContents .= "2,bob,bob@example.com\r\n";
        static::assertSame($expectedContents, \file_get_contents('vfs://output/data.csv'));
    }

    public function testExportWithCarriageReturn()
    {
        $config = new ExporterConfig();
        $config->setNewline("\r");
        $exporter = new Exporter($config);
        $exporter->unstrict();

        static::assertFileNotExists('vfs://output/data.csv');
        $exporter->export('vfs://output/data.csv', [
            ['aaa', 'bbb', 'ccc', 'dddd'],
            ['123', '456', '789'],
            ['"aaa"', '"bbb"', '', ''],
        ]);

        static::assertFileExists('vfs://output/data.csv');
        $expectedContents = "aaa,bbb,ccc,dddd\r";
        $expectedContents .= "123,456,789\r";
        $expectedContents .= '"""aaa""","""bbb""",,' . "\r";
        static::assertSame($expectedContents, \file_get_contents('vfs://output/data.csv'));
    }

    public function testUnstrict()
    {
        $config = new ExporterConfig();
        $exporter = new Exporter($config);
        static::assertAttributeSame(true, 'strict', $exporter);
        $exporter->unstrict();
        static::assertAttributeSame(false, 'strict', $exporter);
    }

    public function testStrict()
    {
        $this->expectException(StrictViolationException::class);
        $config = new ExporterConfig();
        $exporter = new Exporter($config);

        $exporter->export('vfs://output/data.csv', [
            ['a', 'b', 'c'],
            ['a', 'b', 'c'],
            ['a', 'b'],
        ]);
    }

    /**
     * @requires PHP 5.4
     */
    public function testThrowingIOExceptionWhenFailedToWriteFile()
    {
        $noWritableCsv = 'vfs://output/no-writable.csv';
        \touch($noWritableCsv);
        \chmod($noWritableCsv, 0444);

        static::assertFalse(\is_writable($noWritableCsv));

        $config = new ExporterConfig();
        $exporter = new Exporter($config);

        $e = null;

        try {
            $exporter->export($noWritableCsv, [
                ['a', 'b', 'c'],
            ]);
        } catch (IOException $e) {
        }

        static::assertTrue($e instanceof IOException);
        static::assertContains('failed to open', $e->getMessage());
    }

    public function testEncoding()
    {
        $csv = 'vfs://output/euc.csv';
        static::assertFileNotExists($csv);

        $config = new ExporterConfig();
        $config->setToCharset('EUC-JP');
        $config->setNewline("\n");
        $exporter = new Exporter($config);

        $exporter->export($csv, [
            ['あ', 'い', 'う', 'え', 'お'],
        ]);

        static::assertFileEquals(__DIR__ . '/csv_files/euc-jp.csv', $csv);
    }

    public function testWithoutEncoding()
    {
        $csv = 'vfs://output/utf-8.csv';
        static::assertFileNotExists($csv);

        $config = new ExporterConfig();
        $config->setNewline("\n");
        $exporter = new Exporter($config);

        $exporter->export($csv, [
            ['✔', '✔', '✔'],
            ['★', '★', '★'],
        ]);

        static::assertFileEquals(__DIR__ . '/csv_files/utf-8.csv', $csv);
    }

    public function testUnseekableWrapperAndCustomNewlineCode()
    {
        $config = new ExporterConfig();
        $config->setNewline("\r\n");
        $exporter = new Exporter($config);

        \ob_start();
        $exporter->export('php://output', [
            ['a', 'b', 'c'],
            ['1', '2', '3'],
        ]);
        $output = \ob_get_clean();

        $expectedCount = "a,b,c\r\n1,2,3\r\n";
        static::assertSame($expectedCount, $output);
    }

    public function testMultipleLineColumns()
    {
        $csv = 'vfs://output/multiple-lines.csv';
        static::assertFileNotExists($csv);

        $config = new ExporterConfig();
        $config->setNewline("\r\n");
        $exporter = new Exporter($config);

        $exporter->export($csv, [
            ["line1\r\nline2\r\nline3", 'single-line'],
            ["line1\r\nline2\r\nline3", 'single-line'],
            ["line1\r\nline2\r\nline3", 'single-line'],
        ]);

        static::assertFileEquals(__DIR__ . '/csv_files/multiple-lines.csv', $csv);
    }
}
