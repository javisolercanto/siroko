<?php

declare(strict_types=1);

namespace App\Tests\Cart\Application\Create;

use App\Cart\Application\Create\CartCreateCommand;
use App\Tests\BaseTestCase;

class CartCreateCommandTest extends BaseTestCase
{
    public function testCartCreateCommandFromPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';
        $data = ['owner_id' => $uuid];

        $command = CartCreateCommand::fromPrimitives(aggregateId: '', primitives: $data);

        $this->assertInstanceOf(CartCreateCommand::class, $command);
        $this->assertSame($uuid, $command->ownerId);
        $this->assertSame($data, $command->toPrimitives());
        $this->assertSame('', $command->aggregateId());
    }

    public function testCartCreateCommandToPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';
        $command = new CartCreateCommand(ownerId: $uuid);

        $expected = ['owner_id' => $uuid];

        $this->assertSame($expected, $command->toPrimitives());
    }
}
