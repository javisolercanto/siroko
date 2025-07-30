<?php

declare(strict_types=1);

namespace App\Tests\Cart\Domain;

use App\_Shared\Message\Event\Domain\DomainEventBus;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartId;
use App\Cart\Domain\CartApplicationService;
use App\Cart\Domain\CartRepositoryInterface;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CartApplicationServiceTest extends BaseTestCase
{
    private CartApplicationService $applicationService;
    private CartRepositoryInterface&MockObject $repository;
    private DomainEventBus&MockObject $eventBus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(CartRepositoryInterface::class);
        $this->eventBus = $this->createMock(DomainEventBus::class);

        $this->applicationService = new CartApplicationService(
            repository: $this->repository,
            eventBus: $this->eventBus,
        );
    }

    public function testCartApplicationServiceFind(): void
    {
        $cart = Cart::create(['owner_id' => '123e4567-e89b-12d3-a456-426614174000']);
        $cartId = new CartId($cart->id());

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($cartId)
            ->willReturn($cart);

        $result = $this->applicationService->find($cartId);

        $this->assertSame($cart, $result);
    }

    public function testCartApplicationServiceSave(): void
    {
        $cart = Cart::create(['owner_id' => '123e4567-e89b-12d3-a456-426614174000']);

        $this->eventBus
            ->expects($this->once())
            ->method('publish');

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($cart);

        $result = $this->applicationService->save($cart);

        $this->assertSame($cart, $result);
    }
}
