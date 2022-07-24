<?php

namespace SoucePot\Bag;

use Throwable;
use PHPUnit\Framework\TestCase;
use SourcePot\Bag\ReadOnlyBag;

class ReadOnlyBagTest extends TestCase
{
    public function testCanSetNewValue(): void
    {
        $bag = new ReadOnlyBag();

        $bag->set('test', 'value');

        $this->assertTrue($bag->has('test'));
    }

    public function testCannotOverwriteExistingValue(): void
    {
        $bag = new ReadOnlyBag([
            'test' => 'value'
        ]);

        $this->expectException(Throwable::class);
        $bag->set('test', 'value');
    }
}