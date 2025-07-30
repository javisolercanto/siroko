<?php

declare(strict_types=1);

namespace App\Tests\Order\Application;

use App\Order\Application\Create\OrderCreateCommand;
use App\Tests\BaseTestCase;

class OrderCreateCommandTest extends BaseTestCase
{
    public function testOrderCreateCommandFromPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';
        $cartId = '0198522d-8352-72a9-9c3e-8ae625aca59f';

        $data = ['owner_id' => $uuid, 'cart_id' => $cartId];

        $command = OrderCreateCommand::fromPrimitives(aggregateId: '', primitives: $data);

        $this->assertInstanceOf(OrderCreateCommand::class, $command);
        $this->assertSame($uuid, $command->ownerId);
        $this->assertSame($data, $command->toPrimitives());
        $this->assertSame('', $command->aggregateId());
    }

    public function testOrderCreateCommandToPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';
        $cartId = '0198522d-8352-72a9-9c3e-8ae625aca59f';

        $command = new OrderCreateCommand(
            ownerId: $uuid,
            cartId: $cartId,
        );

        $expected = ['owner_id' => $uuid, 'cart_id' => $cartId];

        $this->assertSame($expected, $command->toPrimitives());
    }
}
