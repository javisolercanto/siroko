<?php

declare(strict_types=1);

namespace App\Tests\Order\Infrastructure\Persistence;

use App\Order\Infrastructure\Persistence\OrderOrm;
use App\Tests\BaseTestCase;

class OrderOrmTest extends BaseTestCase
{
    public function testOrderOrmNew(): void
    {
        $id = '123e4567-e89b-12d3-a456-426614174000';
        $ownerId = '123e4567-e89b-12d3-a456-426614174001';
        $cartId = '123e4567-e89b-12d3-a456-426614174002';
        $date = new \DateTimeImmutable();

        $orderOrm = new OrderOrm($id, $ownerId, $cartId, $date);

        $this->assertSame($id, $orderOrm->id());
        $this->assertSame($ownerId, $orderOrm->ownerId());
        $this->assertSame($cartId, $orderOrm->cartId());
        $this->assertSame($date, $orderOrm->date());
    }
}
