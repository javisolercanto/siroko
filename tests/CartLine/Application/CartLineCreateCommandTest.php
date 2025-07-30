<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Application;

use App\CartLine\Application\Create\CartLineCreateCommand;
use App\Tests\BaseTestCase;

class CartLineCreateCommandTest extends BaseTestCase
{
    public function testCartLineCreateCommandFromPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';
        $data = [
            'owner_id' => $uuid,
            'cart_id' => '0198522d-8352-72a9-9c3e-8ae625aca59f',
            'product_id' => '0198522d-8352-72a9-9c3e-8ae625aca59g',
            'quantity' => 1,
        ];

        $command = CartLineCreateCommand::fromPrimitives(aggregateId: '', primitives: $data);

        $this->assertInstanceOf(CartLineCreateCommand::class, $command);
        $this->assertSame($uuid, $command->ownerId);
        $this->assertSame($data, $command->toPrimitives());
        $this->assertSame('', $command->aggregateId());
    }

    public function testCartLineCreateCommandToPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';
        $ownerId = '0198522d-8352-72a9-9c3e-8ae625aca59g';
        $cartId = '0198522d-8352-72a9-9c3e-8ae625aca59f';
        $productId = '0198522d-8352-72a9-9c3e-8ae625aca59g';
        $quantity = 1;

        $command = new CartLineCreateCommand(
            ownerId: $uuid,
            cartId: $cartId,
            productId: $productId,
            quantity: $quantity,
        );

        $expected = [
            'owner_id' => $uuid,
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ];

        $this->assertSame($expected, $command->toPrimitives());
    }
}
