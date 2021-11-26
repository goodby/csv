<?php

namespace Goodby\CSV\Export\Tests\Standard\Unit\Collection;

use Goodby\CSV\Export\Standard\Collection\CallbackCollection;
use PHPUnit\Framework\TestCase;

class CallbackCollectionTest extends TestCase
{
    public function testSample()
    {
        $data = [];
        $data[] = ['user', 'name1'];
        $data[] = ['user', 'name2'];
        $data[] = ['user', 'name3'];

        $collection = new CallbackCollection($data, function($mixed) {
            return $mixed;
        });

        $index = 1;
        foreach ($collection as $each) {
            static::assertEquals($each[0], 'user');
            static::assertEquals($each[1], 'name' . $index);
            ++$index;
        }
    }

    public function testIteratorAggregate()
    {
        $data = [];
        $data[] = ['user', 'name1'];
        $data[] = ['user', 'name2'];
        $data[] = ['user', 'name3'];

        $iterator = new SampleAggIterator($data);

        $collection = new CallbackCollection($iterator, function($mixed) {
            return $mixed;
        });

        $index = 1;
        foreach ($collection as $each) {
            static::assertEquals($each[0], 'user');
            static::assertEquals($each[1], 'name' . $index);
            ++$index;
        }
    }
}
