<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Infrastructure\Persistence;

use App\CartLine\Infrastructure\Persistence\CartLineOrm;
use App\Tests\BaseTestCase;

class CartLineOrmTest extends BaseTestCase
{
    public function testCartLineOrmNew(): void
    {
        $id = '123e4567-e89b-12d3-a456-426614174000';
        $ownerId = '123e4567-e89b-12d3-a456-426614174001';
        $cartId = '123e4567-e89b-12d3-a456-426614174001';
        $productId = '123e4567-e89b-12d3-a456-426614174002';
        $quantity = 1;

        $cartlineOrm = new CartLineOrm(
            id: $id,
            ownerId: $ownerId,
            cartId: $cartId,
            productId: $productId,
            quantity: $quantity,
        );

        $this->assertSame($id, $cartlineOrm->id());
        $this->assertSame($ownerId, $cartlineOrm->ownerId());
        $this->assertSame($cartId, $cartlineOrm->cartId());
        $this->assertSame($productId, $cartlineOrm->productId());
        $this->assertSame($quantity, $cartlineOrm->quantity());
    }
}
