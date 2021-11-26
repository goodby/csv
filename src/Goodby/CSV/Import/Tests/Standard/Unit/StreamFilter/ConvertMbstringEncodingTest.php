<?php

namespace Goodby\CSV\Import\Tests\Standard\Unit\StreamFilter;

use Goodby\CSV\Import\Standard\StreamFilter\ConvertMbstringEncoding;
use PHPUnit\Framework\TestCase;

class ConvertMbstringEncodingTest extends TestCase
{
    private $internalEncodingBackup;

    public function setUp()
    {
        $this->internalEncodingBackup = \mb_internal_encoding();
    }

    public function tearDown()
    {
        \mb_internal_encoding($this->internalEncodingBackup);
    }

    public function testGetFilterName()
    {
        static::assertSame('convert.mbstring.encoding.*', ConvertMbstringEncoding::getFilterName());
    }

    public function testOneParameter()
    {
        $filterString = 'convert.mbstring.encoding.EUC-JP';
        \mb_internal_encoding('UTF-7');
        $filter = new ConvertMbstringEncoding();
        $filter->filtername = $filterString;
        $filter->onCreate();
        static::assertAttributeSame('EUC-JP', 'fromCharset', $filter);
        static::assertAttributeSame('UTF-7', 'toCharset', $filter);
    }

    public function testTwoParameters()
    {
        $filterString = 'convert.mbstring.encoding.SJIS-win:UTF-8';
        \mb_internal_encoding('UTF-7');
        $filter = new ConvertMbstringEncoding();
        $filter->filtername = $filterString;
        $filter->onCreate();
        static::assertAttributeSame('SJIS-win', 'fromCharset', $filter);
        static::assertAttributeSame('UTF-8', 'toCharset', $filter);
    }

    public function testWhenInvalidParameterGivenItReturnsFalse()
    {
        $filterString = 'convert.mbstring.encoding.@#$#!%^^';
        $filter = new ConvertMbstringEncoding();
        $filter->filtername = $filterString;
        static::assertFalse($filter->onCreate());
    }

    public function testRegisterFilter()
    {
        ConvertMbstringEncoding::register();
        $filterName = ConvertMbstringEncoding::getFilterName();
        $registeredFilters = \stream_get_filters();
        static::assertTrue(\in_array($filterName, $registeredFilters));
    }
}
