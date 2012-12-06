<?php

namespace Goodby\CSV\Export\Tests\Standard\Join;

use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;

use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;

class ExporterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->root = vfsStream::setup('output');
    }

    public function testExport()
    {
        $config = new ExporterConfig();
        $exporter = new Exporter($config);

        $this->assertFileNotExists('vfs://output/data.csv');
        $exporter->export('vfs://output/data.csv', array(
            array('ID', 'name',  'email'),
            array('1',  'alice', 'alice@example.com'),
            array('2',  'bob',   'bob@example.com'),
        ));

        $this->assertFileExists('vfs://output/data.csv');
        $expectedContents = "ID,name,email\r\n";
        $expectedContents .= "1,alice,alice@example.com\r\n";
        $expectedContents .= "2,bob,bob@example.com\r\n";
        $this->assertSame($expectedContents, file_get_contents('vfs://output/data.csv'));
    }

    public function test_export_with_carriage_return()
    {
        $config = new ExporterConfig();
        $config->setNewline("\r");
        $exporter = new Exporter($config);

        $this->assertFileNotExists('vfs://output/data.csv');
        $exporter->export('vfs://output/data.csv', array(
            array('aaa', 'bbb', 'ccc', 'dddd'),
            array('123', '456', '789'),
            array('"aaa"', '"bbb"', '', ''),
        ));

        $this->assertFileExists('vfs://output/data.csv');
        $expectedContents = "aaa,bbb,ccc,dddd\r";
        $expectedContents .= "123,456,789\r";
        $expectedContents .= '"""aaa""","""bbb""",,'."\r";
        $this->assertSame($expectedContents, file_get_contents('vfs://output/data.csv'));
    }
}
