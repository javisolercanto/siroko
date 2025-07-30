<?php

declare(strict_types=1);

namespace App\Tests\Cart\Domain\Entity;

use App\Cart\Domain\Entity\CartProcessed;
use App\Tests\BaseTestCase;

class CartProcessedTest extends BaseTestCase
{
    public function testNewCartProcessed()
    {
        $expected = true;

        $processed = new CartProcessed($expected);

        $this->assertInstanceOf(CartProcessed::class, $processed);
        $this->assertEquals($expected, $processed->value());
    }
}
