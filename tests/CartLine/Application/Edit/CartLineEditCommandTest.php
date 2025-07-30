<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Application\Edit;

use App\CartLine\Application\Edit\CartLineEditCommand;
use App\Tests\BaseTestCase;

class CartLineEditCommandTest extends BaseTestCase
{
    public function testCartLineEditCommandFromPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';
        $data = [
            'owner_id' => '0198522d-8352-72a9-9c3e-8ae625aca59f',
            'quantity' => 2,
        ];

        $command = CartLineEditCommand::fromPrimitives(aggregateId: $uuid, primitives: $data);

        $this->assertInstanceOf(CartLineEditCommand::class, $command);
        $this->assertSame($uuid, $command->aggregateId());
        $this->assertSame($data, $command->toPrimitives());
    }

    public function testCartLineEditCommandToPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';
        $ownerId = '0198522d-8352-72a9-9c3e-8ae625aca59f';
        $quantity = 2;

        $command = new CartLineEditCommand(
            cartLineId: $uuid,
            ownerId: $ownerId,
            quantity: $quantity,
        );

        $expected = [
            'owner_id' => $ownerId,
            'quantity' => $quantity,
        ];

        $this->assertSame($expected, $command->toPrimitives());
    }
}
