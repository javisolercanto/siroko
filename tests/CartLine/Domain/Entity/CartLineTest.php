<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Domain\Entity;

use App\CartLine\Domain\Entity\CartLine;
use App\Tests\BaseTestCase;

class CartLineTest extends BaseTestCase
{
    public function testCartLineCreate(): void
    {
        $primitives = [
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
            'cart_id' => '123e4567-e89b-12d3-a456-426614174000',
            'product_id' => '123e4567-e89b-12d3-a456-426614174000',
            'quantity' => 1,
        ];

        $cartline = CartLine::create($primitives);

        $this->assertInstanceOf(CartLine::class, $cartline);
        $this->assertEquals($primitives['owner_id'], $cartline->ownerId());
        $this->assertEquals($primitives['cart_id'], $cartline->cartId());
        $this->assertEquals($primitives['product_id'], $cartline->productId());
        $this->assertEquals($primitives['quantity'], $cartline->quantity());
        $this->assertNotNull($cartline->id());
    }

    public function testCartLinePrimitives(): void
    {
        $primitives = [
            'id' => '0198405b-4f03-737c-950d-d14e39290e5c',
            'owner_id' => '0198405b-4f03-737c-950d-d14e39290e5d',
            'cart_id' => '0198405b-4f03-737c-950d-d14e39290e5e',
            'product_id' => '0198405b-4f03-737c-950d-d14e39290e5f',
            'quantity' => 1,
        ];

        $cartline = CartLine::fromPrimitives($primitives);

        $this->assertInstanceOf(CartLine::class, $cartline);
        $this->assertEquals($primitives['owner_id'], $cartline->ownerId());
        $this->assertEquals($primitives['cart_id'], $cartline->cartId());
        $this->assertEquals($primitives['product_id'], $cartline->productId());
        $this->assertEquals($primitives['quantity'], $cartline->quantity());
        $this->assertEquals($primitives['id'], $cartline->id());

        $this->assertEquals($primitives, $cartline->toPrimitives());
    }
}
