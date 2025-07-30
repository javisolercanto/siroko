<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Domain;

use App\_Shared\Message\Event\Domain\DomainEventBus;
use App\CartLine\Domain\Entity\CartLine;
use App\CartLine\Domain\Entity\CartLineId;
use App\CartLine\Domain\CartLineApplicationService;
use App\CartLine\Domain\CartLineRepositoryInterface;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CartLineApplicationServiceTest extends BaseTestCase
{
    private CartLineApplicationService $applicationService;
    private CartLineRepositoryInterface&MockObject $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(CartLineRepositoryInterface::class);
        $eventBus = $this->createMock(DomainEventBus::class);

        $this->applicationService = new CartLineApplicationService(
            eventBus: $eventBus,
            repository: $this->repository,
        );
    }

    public function testCartLineApplicationServiceFind(): void
    {
        $cartline = CartLine::create([
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
            'cart_id' => '123e4567-e89b-12d3-a456-426614174001',
            'product_id' => '123e4567-e89b-12d3-a456-426614174002',
            'quantity' => 1,
        ]);

        $cartlineId = new CartLineId($cartline->id());

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($cartlineId)
            ->willReturn($cartline);

        $result = $this->applicationService->find($cartlineId);

        $this->assertSame($cartline, $result);
    }

    public function testCartLineApplicationServiceSave(): void
    {
        $cartline = CartLine::create([
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
            'cart_id' => '123e4567-e89b-12d3-a456-426614174001',
            'product_id' => '123e4567-e89b-12d3-a456-426614174002',
            'quantity' => 1,
        ]);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($cartline);

        $result = $this->applicationService->save($cartline);

        $this->assertSame($cartline, $result);
    }
}
