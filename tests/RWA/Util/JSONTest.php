<?php

namespace RWA\Util;

use PHPUnit\Framework\TestCase;

class JSONTest extends TestCase
{
    public function testEncodeArray(): void
    {
        $data = [
            'test' => 'value'
        ];

        $expected = '{"test":"value"}';

        $output = JSON::stringify($data);

        $this->assertEquals($expected, $output);
    }

    public function testEncodeObject(): void
    {
        $data = new \stdclass;
        $data->test = 'value';

        $expected = '{"test":"value"}';

        $output = JSON::stringify($data);

        $this->assertEquals($expected, $output);
    }

    public function testDecodeValidData(): void
    {
        $json = '{"test":"value"}';

        $expected = [
            'test' => 'value'
        ];

        $output = JSON::parse($json);

        $this->assertEquals($expected, $output);
    }

    public function testDecodeInvalidData(): void
    {
        $json = '{test:"value"}';
        
        $this->expectException(\JsonException::class);

        $output = JSON::parse($json);
    }
}
