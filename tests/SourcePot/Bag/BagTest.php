<?php

namespace SourcePot\Bag;

use PHPUnit\Framework\TestCase;

class BagTest extends TestCase
{
    public function testCanInstantiate(): void
    {
        $bag = new Bag();

        $this->assertInstanceOf(Bag::class, $bag);
    }

    public function testCanInstantiateWithValues(): void
    {
        $bag = new Bag([
            'test' => 'value'
        ]);

        $this->assertInstanceOf(Bag::class, $bag);
    }

    public function testCanInstantiateWithInvalidValues(): void
    {
        $bag = new Bag([
            'test' => new \stdclass
        ]);

        $this->assertInstanceOf(Bag::class, $bag);
    }

    public function testHas()
    {
        $bag = new Bag([
            'test' => 'value',
        ]);

        $this->assertTrue($bag->has('test'));

        $bag = new Bag();
        $bag->set('test', 'value');

        $this->assertTrue($bag->has('test'));
    }

    public function testGet(): void
    {
        $bag = new Bag([
            'test' => 'value'
        ]);

        $bag->get('test');

        $this->assertEquals('value', $bag->get('test'));

        $bag = new Bag();
        $bag->set('test', 'value');
        
        $this->assertEquals('value', $bag->get('test'));
    }

    public function testSet(): void
    {
        $bag = new Bag();

        $bag->set('test', 'value');

        $this->assertTrue($bag->has('test'));
    }

    public function testAll(): void
    {
        $data = [
            'test1' => 'value1',
            'test2' => 'value2'
        ];

        $bag = new Bag($data);

        $this->assertEquals($data, $bag->all());
    }
}
