<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Application\Delete;

use App\CartLine\Application\Delete\CartLineDeleteCommand;
use App\Tests\BaseTestCase;

class CartLineDeleteCommandTest extends BaseTestCase
{
    public function testCartLineDeleteCommandFromPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';
        $data = [
            'owner_id' => '0198522d-8352-72a9-9c3e-8ae625aca59f',
        ];

        $command = CartLineDeleteCommand::fromPrimitives(aggregateId: $uuid, primitives: $data);

        $this->assertInstanceOf(CartLineDeleteCommand::class, $command);
        $this->assertSame($uuid, $command->aggregateId());
        $this->assertSame($data, $command->toPrimitives());
    }

    public function testCartLineDeleteCommandToPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';
        $ownerId = '0198522d-8352-72a9-9c3e-8ae625aca59f';

        $command = new CartLineDeleteCommand(id: $uuid, ownerId: $ownerId);

        $expected = [
            'owner_id' => $ownerId,
        ];

        $this->assertSame($expected, $command->toPrimitives());
    }
}
