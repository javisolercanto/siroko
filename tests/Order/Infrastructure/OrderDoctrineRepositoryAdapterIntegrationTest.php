<?php

declare(strict_types=1);

namespace App\Tests\Order\Infrastructure;

use App\_Shared\Message\AggregateRoot\Entity\UuidValueObject;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderId;
use App\Order\Domain\Entity\OrderOwnerId;
use App\Order\Infrastructure\OrderDoctrineRepositoryAdapter;
use App\Tests\BaseTestCase;

class OrderDoctrineRepositoryAdapterIntegrationTest extends BaseTestCase
{
    private OrderDoctrineRepositoryAdapter $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new OrderDoctrineRepositoryAdapter($this->em);
    }

    public function testOrderDoctrineRepositoryAdapterIntegrationFind(): void
    {
        $id = '01985828-6df1-7a5b-aa5b-9af694856f30';

        $found = $this->repository->find(new OrderId($id));

        $this->assertNotNull($found);
        $this->assertSame($id, $found->id());
        $this->assertInstanceOf(Order::class, $found);
    }

    public function testOrderDoctrineRepositoryAdapterIntegrationFindByOwnerId(): void
    {
        $ownerId = '123e4567-e89b-12d3-a456-426614174000';

        $found = $this->repository->findByOwnerId(new OrderOwnerId($ownerId));

        $this->assertNotEmpty($found);
        $this->assertSame($ownerId, $found[0]->ownerId());
        $this->assertInstanceOf(Order::class, $found[0]);
    }

    public function testOrderDoctrineRepositoryAdapterIntegrationFindError(): void
    {
        $id = '0198521d-7868-7d8f-9737-9789a7e1604f';

        $found = $this->repository->find(new OrderId($id));

        $this->assertNull($found);
    }

    public function testOrderDoctrineRepositoryAdapterIntegrationSaveAndFind(): void
    {
        $order = Order::create([
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
            'cart_id' => '123e4567-e89b-12d3-a456-426614174001',
        ]);

        $id = $order->id();

        $this->repository->save($order);

        $found = $this->repository->find(new OrderId($id));

        $this->assertNotNull($found);
        $this->assertSame($order->id(), $found->id());
        $this->assertSame($order->ownerId(), $found->ownerId());
        $this->assertSame($order->cartId(), $found->cartId());
        $this->assertInstanceOf(Order::class, $found);
    }
}
