<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Domain\Entity;

use App\CartLine\Domain\Entity\CartLineQuantity;
use App\CartLine\Domain\Exception\CartLineInvalidQuantityException;
use App\Tests\BaseTestCase;

class CartLineQuantityTest extends BaseTestCase
{
    public function testNewCartLineQuantity()
    {
        $expected = 1;

        $ownerId = new CartLineQuantity($expected);

        $this->assertInstanceOf(CartLineQuantity::class, $ownerId);
        $this->assertEquals($expected, $ownerId->value());
    }

    public function testInvalidCartLineQuantity()
    {
        $this->expectException(CartLineInvalidQuantityException::class);
        $this->expectExceptionMessage('Quantity must be greater than 0');
        new CartLineQuantity(-10);
    }
}
