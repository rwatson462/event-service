<?php

namespace SourcePot\Util;

use PHPUnit\Framework\TestCase;
use SourcePot\Util\Base64Url;

class Base64UrlTest extends TestCase
{
    protected function dataProviderCanEncode(): iterable
    {
        yield 'basic string' => [
            'hello, world',
            'aGVsbG8sIHdvcmxk',
        ];
        yield 'base64 with forward slash' => [
            'subjects?_d=1',
            'c3ViamVjdHM_X2Q9MQ==',
        ];
        // @todo find a string that encodes with a '+' in it
    }

    /**
     * @dataProvider dataProviderCanEncode
     */
    public function testCanEncode(string $input, string $expected): void
    {
        $output = Base64Url::encode($input);

        $this->assertEquals($expected, $output);
    }

    protected function dataProviderCanDecode(): iterable
    {
        yield 'basic string' => [
            'aGVsbG8sIHdvcmxk',
            'hello, world',
        ];
        yield 'base64 with forward slash' => [
            'c3ViamVjdHM_X2Q9MQ==',
            'subjects?_d=1',
        ];
        // @todo find a string that encodes with a '+' in it
    }

    /**
     * @dataProvider dataProviderCanDecode
     */
    public function testCanDecode($input, $expected): void
    {
        $output = Base64Url::decode($input);

        $this->assertEquals($expected, $output);
    }
}