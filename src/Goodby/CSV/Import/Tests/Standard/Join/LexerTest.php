<?php

namespace Goodby\CSV\Import\Tests\Standard\Join;

use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\LexerConfig;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class LexerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testShiftJisCSV()
    {
        $shiftJisCsv = CSVFiles::getShiftJisCsv();
        $lines = [
            ['あ', 'い', 'う', 'え', 'お'],
            ['日本語', '日本語', '日本語', '日本語', '日本語'],
            ['ぱ', 'ぴ', 'ぷ', 'ぺ', 'ぽ'],
            ['"quoted"', "a'quote'", 'a, b and c', '', ''],
        ];

        $interpreter = $this->prophesize(Interpreter::class);
        $interpreter->interpret(Argument::cetera())
            ->shouldBeCalled()
            ->willReturn(
                $lines[0],
                $lines[1],
                $lines[2],
                $lines[3]
            );

        $config = new LexerConfig();
        $config->setToCharset('UTF-8')->setFromCharset('SJIS-win');
        $lexer = new Lexer($config);
        $lexer->parse($shiftJisCsv, $interpreter->reveal());
    }

    public function testMacExcelCsv()
    {
        $csv = CSVFiles::getMacExcelCsv();
        $lines = CSVFiles::getMacExcelLines();

        $interpreter = $this->prophesize(Interpreter::class);
        $interpreter->interpret(Argument::cetera())
            ->shouldBeCalled()
            ->willReturn(
                $lines[0],
                $lines[1]
            );

        $config = new LexerConfig();
        $lexer = new Lexer($config);
        $lexer->parse($csv, $interpreter->reveal());
    }

    public function testTabSeparatedCsv()
    {
        $csv = CSVFiles::getTabSeparatedCsv();
        $lines = CSVFiles::getTabSeparatedLines();

        $interpreter = $this->prophesize(Interpreter::class);
        $interpreter->interpret(Argument::cetera())
            ->shouldBeCalled()
            ->willReturn(
                $lines[0],
                $lines[1]
            );

        $config = new LexerConfig();
        $config->setDelimiter("\t");
        $lexer = new Lexer($config);
        $lexer->parse($csv, $interpreter->reveal());
    }

    public function testColonSeparatedCsv()
    {
        $csv = CSVFiles::getColonSeparatedCsv();
        $lines = CSVFiles::getColonSeparatedLines();

        $interpreter = $this->prophesize(Interpreter::class);
        $interpreter->interpret(Argument::cetera())
            ->shouldBeCalled()
            ->willReturn(
                $lines[0],
                $lines[1]
            );

        $config = new LexerConfig();
        $config->setDelimiter(':');
        $lexer = new Lexer($config);
        $lexer->parse($csv, $interpreter->reveal());
    }

    public function testUtf8Csv()
    {
        $csv = CSVFiles::getUtf8Csv();
        $lines = CSVFiles::getUtf8Lines();

        $interpreter = $this->prophesize(Interpreter::class);
        $interpreter->interpret(Argument::cetera())
            ->shouldBeCalled()
            ->willReturn(
                $lines[0],
                $lines[1]
            );

        $config = new LexerConfig();
        $lexer = new Lexer($config);
        $lexer->parse($csv, $interpreter->reveal());
    }

    /**
     * When import CSV file with data in Japanese (2 bytes character),
     * data imported to database with error encoding.
     *
     * @see https://github.com/goodby/csv/issues/5
     */
    public function testIssue5()
    {
        $csvFilename = CSVFiles::getIssue5CSV();

        $csvContents = [];

        $config = new LexerConfig();
        $config
            ->setToCharset('UTF-8')
            ->setFromCharset('UTF-8');
        $lexer = new Lexer($config);
        $interpreter = new Interpreter();
        $interpreter->addObserver(function(array $columns) use (&$csvContents) {
            $csvContents[] = $columns;
        });

        $lexer->parse($csvFilename, $interpreter);

        static::assertSame([
            ['ID', 'NAME', 'MAKER'],
            ['1', 'スティック型クリーナ', 'alice_updated@example.com'],
            ['2', 'bob', 'bob@example.com'],
            ['14', 'スティック型クリーナ', 'tho@eample.com'],
            ['16', 'スティック型', 'carot@eample.com'],
        ], $csvContents);
    }

    public function testIgnoreHeader()
    {
        $csvFilename = CSVFiles::getIssue5CSV();

        $config = new LexerConfig();
        $config
          ->setIgnoreHeaderLine(true)
          ->setToCharset('UTF-8')
          ->setFromCharset('UTF-8');

        $lexer = new Lexer($config);

        $interpreter = new Interpreter();
        $interpreter->addObserver(function(array $columns) use (&$csvContents) {
            $csvContents[] = $columns;
        });

        $lexer->parse($csvFilename, $interpreter);
        static::assertSame([
            ['1', 'スティック型クリーナ', 'alice_updated@example.com'],
            ['2', 'bob', 'bob@example.com'],
            ['14', 'スティック型クリーナ', 'tho@eample.com'],
            ['16', 'スティック型', 'carot@eample.com'],
        ], $csvContents);
    }

    public function testInstantiationWithoutConfig()
    {
        $lexer = new Lexer();

        static::assertInstanceOf(Lexer::class, $lexer);
    }
}
