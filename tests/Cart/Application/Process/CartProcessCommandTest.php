<?php

declare(strict_types=1);

namespace App\Tests\Cart\Application\Process;

use App\Cart\Application\Process\CartProcessCommand;
use App\Tests\BaseTestCase;

class CartProcessCommandTest extends BaseTestCase
{
    public function testCartProcessCommandFromPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';

        $command = CartProcessCommand::fromPrimitives(aggregateId: $uuid, primitives: []);

        $this->assertInstanceOf(CartProcessCommand::class, $command);
        $this->assertSame($uuid, $command->aggregateId());
    }

    public function testCartProcessCommandToPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';
        $command = new CartProcessCommand(cartId: $uuid);

        $this->assertSame([], $command->toPrimitives());
    }
}
