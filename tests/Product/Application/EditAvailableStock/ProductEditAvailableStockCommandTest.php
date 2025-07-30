<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\EditAvailableStock;

use App\Product\Application\EditAvailableStock\ProductEditAvailableStockCommand;
use App\Tests\BaseTestCase;

class ProductEditAvailableStockCommandTest extends BaseTestCase
{
    public function testProductEditAvailableStockCommandFromPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';
        $data = [
            'quantity' => 2,
        ];

        $command = ProductEditAvailableStockCommand::fromPrimitives(aggregateId: $uuid, primitives: $data);

        $this->assertInstanceOf(ProductEditAvailableStockCommand::class, $command);
        $this->assertSame($uuid, $command->aggregateId());
        $this->assertSame($data, $command->toPrimitives());
    }

    public function testProductEditAvailableStockCommandToPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';
        $quantity = 2;

        $command = new ProductEditAvailableStockCommand(
            productId: $uuid,
            quantity: $quantity,
        );

        $expected = [
            'quantity' => $quantity,
        ];

        $this->assertSame($expected, $command->toPrimitives());
    }
}
