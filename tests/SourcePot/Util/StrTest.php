<?php

namespace SourcePot\Util;

use PHPUnit\Framework\TestCase;
use SourcePot\Util\Str;

class StrTest extends TestCase
{
    public function testCanInstantiate(): void
    {
        $str = new Str('test');
        $this->assertInstanceOf(Str::class, $str);
        
        $str = Str::from('test');
        $this->assertInstanceOf(Str::class, $str);
    }

    public function testCanCopy(): void
    {
        $str = new Str('test');

        $str2 = $str->copy();

        $this->assertNotSame($str, $str2);
    }

    public function testUnwrap(): void
    {
        $str = new Str('test');

        $this->assertEquals('test', $str->unwrap());
    }

    public function testUpperCase(): void
    {
        $str = new Str('test');

        $upper = $str->toUpper();

        $this->assertEquals('TEST', $upper->unwrap());
    }

    public function testLowerCase(): void
    {
        $str = new Str('TEST');

        $upper = $str->toLower();

        $this->assertEquals('test', $upper->unwrap());
    }

    public function testLength(): void
    {
        $str = new Str('test');

        $this->assertEquals(4, $str->length());
    }

    public function testStartsWith(): void
    {
        $str = new Str('test');
        $start = 'te';

        $this->assertTrue($str->startsWith($start));
        $this->assertTrue($str->startsWith(Str::from($start)));
    }

    public function testEndsWith(): void
    {
        $str = new Str('test');
        $end = 'st';

        $this->assertTrue($str->endsWith($end));
        $this->assertTrue($str->endsWith(Str::from($end)));
    }

    public function testSlice(): void
    {
        $str = new Str('hello, world');
        $slice = new Str('o, w');

        $this->assertEquals($slice, $str->slice(4, 4));

        // @todo add more assertions with negative start index, invalid lengths, etc
    }

    public function testEquals(): void
    {
        $str = new Str('test');

        $str2 = new Str('test');
        $string2 = 'test';

        $str3 = new Str('fail');
        $string3 = 'fail';

        $this->assertTrue($str->equals($str2));
        $this->assertTrue($str->equals($string2));

        $this->assertFalse($str->equals($str3));
        $this->assertFalse($str->equals($string3));
    }

    public function testCompareTo(): void
    {
        $str = new Str('b');
        $str2 = new Str('a');
        $str3 = new Str('c');
        $str4 = new Str('b');

        $this->assertGreaterThan(0, $str->compareTo($str2));
        $this->assertLessThan(0, $str->compareTo($str3));
        $this->assertEquals(0, $str->compareTo($str4));
    }
}