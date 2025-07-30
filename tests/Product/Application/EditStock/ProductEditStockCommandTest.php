<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\EditStock;

use App\Product\Application\EditStock\ProductEditStockCommand;
use App\Tests\BaseTestCase;

class ProductEditStockCommandTest extends BaseTestCase
{
    public function testProductEditStockCommandFromPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';
        $data = [
            'quantity' => 2,
        ];

        $command = ProductEditStockCommand::fromPrimitives(aggregateId: $uuid, primitives: $data);

        $this->assertInstanceOf(ProductEditStockCommand::class, $command);
        $this->assertSame($uuid, $command->aggregateId());
        $this->assertSame($data, $command->toPrimitives());
    }

    public function testProductEditStockCommandToPrimitives(): void
    {
        $uuid = '0198522d-8352-72a9-9c3e-8ae625aca59e';
        $quantity = 2;

        $command = new ProductEditStockCommand(
            productId: $uuid,
            quantity: $quantity,
        );

        $expected = [
            'quantity' => $quantity,
        ];

        $this->assertSame($expected, $command->toPrimitives());
    }
}
