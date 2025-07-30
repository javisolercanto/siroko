<?php

declare(strict_types=1);

namespace App\Tests\Order\Domain;

use App\_Shared\Message\Event\Domain\DomainEventBus;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderId;
use App\Order\Domain\Entity\OrderOwnerId;
use App\Order\Domain\OrderApplicationService;
use App\Order\Domain\OrderRepositoryInterface;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class OrderApplicationServiceTest extends BaseTestCase
{
    private OrderApplicationService $applicationService;
    private OrderRepositoryInterface&MockObject $repository;
    private DomainEventBus&MockObject $eventBus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(OrderRepositoryInterface::class);
        $this->eventBus = $this->createMock(DomainEventBus::class);

        $this->applicationService = new OrderApplicationService(
            repository: $this->repository,
            eventBus: $this->eventBus,
        );
    }

    public function testOrderApplicationServiceFind(): void
    {
        $order = Order::create([
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
            'cart_id' => '123e4567-e89b-12d3-a456-426614174001',
        ]);

        $orderId = new OrderId($order->id());

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($orderId)
            ->willReturn($order);

        $result = $this->applicationService->find($orderId);

        $this->assertSame($order, $result);
    }

    public function testOrderApplicationServiceFindByOwnerId(): void
    {
        $order = Order::create([
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
            'cart_id' => '123e4567-e89b-12d3-a456-426614174001',
        ]);

        $ownerId = new OrderOwnerId($order->ownerId());

        $this->repository
            ->expects($this->once())
            ->method('findByOwnerId')
            ->with($ownerId)
            ->willReturn([$order]);

        $result = $this->applicationService->findByOwnerId($ownerId);

        $this->assertSame([$order], $result);
    }

    public function testOrderApplicationServiceSave(): void
    {
        $order = Order::create([
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
            'cart_id' => '123e4567-e89b-12d3-a456-426614174001',
        ]);

        $this->eventBus
            ->expects($this->once())
            ->method('publish');

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($order);

        $result = $this->applicationService->save($order);

        $this->assertSame($order, $result);
    }
}
