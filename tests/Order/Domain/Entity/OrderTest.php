<?php

declare(strict_types=1);

namespace App\Tests\Order\Domain\Entity;

use App\Order\Domain\Entity\Order;
use App\Tests\BaseTestCase;

class OrderTest extends BaseTestCase
{
    public function testOrderCreate(): void
    {
        $primitives = [
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
            'cart_id' => '123e4567-e89b-12d3-a456-426614174001',
        ];

        $order = Order::create($primitives);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($primitives['owner_id'], $order->ownerId());
        $this->assertNotNull($order->id());
    }

    public function testOrderPrimitives(): void
    {
        $primitives = [
            'id' => '0198405b-4f03-737c-950d-d14e39290e5c',
            'owner_id' => '0198405b-4f03-737c-950d-d14e39290e5d',
            'cart_id' => '0198405b-4f03-737c-950d-d14e39290e5e',
            'date' => new \DateTimeImmutable(),
        ];

        $order = Order::fromPrimitives($primitives);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($primitives['owner_id'], $order->ownerId());
        $this->assertEquals($primitives['cart_id'], $order->cartId());
        $this->assertEquals($primitives['date'], $order->date());
        $this->assertEquals($primitives['id'], $order->id());

        $this->assertEquals($primitives, $order->toPrimitives());
    }
}
