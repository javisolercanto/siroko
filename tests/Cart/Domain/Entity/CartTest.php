<?php

declare(strict_types=1);

namespace App\Tests\Cart\Domain\Entity;

use App\Cart\Domain\Entity\Cart;
use App\Tests\BaseTestCase;

class CartTest extends BaseTestCase
{
    public function testCartCreate(): void
    {
        $primitives = [
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
        ];

        $cart = Cart::create($primitives);

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertEquals($primitives['owner_id'], $cart->ownerId());
        $this->assertNotNull($cart->id());
    }

    public function testCartPrimitives(): void
    {
        $primitives = [
            'id' => '0198405b-4f03-737c-950d-d14e39290e5c',
            'owner_id' => '0198405b-4f03-737c-950d-d14e39290e5d',
            'processed' => true,
        ];

        $cart = Cart::fromPrimitives($primitives);

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertEquals($primitives['owner_id'], $cart->ownerId());
        $this->assertEquals($primitives['id'], $cart->id());

        $this->assertEquals($primitives, $cart->toPrimitives());
    }
}
