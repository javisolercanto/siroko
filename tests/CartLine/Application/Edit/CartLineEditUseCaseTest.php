<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Application\Edit;

use App\_Shared\Message\AggregateRoot\Entity\UuidValueObject;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\CartLine\Application\_Shared\CartLinePersistEnsurements;
use App\CartLine\Application\Edit\CartLineEditCommand;
use App\CartLine\Application\Edit\CartLineEditUseCase;
use App\CartLine\Domain\CartLineApplicationService;
use App\CartLine\Domain\Entity\CartLine;
use App\CartLine\Domain\Exception\CartLineNotFoundException;
use App\CartLine\Domain\Exception\CartLineOutOfStockException;
use App\Tests\BaseTestCase;

class CartLineEditUseCaseTest extends BaseTestCase
{
    public function testCartLineEditUseCase(): void
    {
        $cartline = CartLine::create([
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
            'cart_id' => '123e4567-e89b-12d3-a456-426614174001',
            'product_id' => '019850bc-0cc9-74f3-886f-ff13031f2fc8',
            'quantity' => 1,
        ]);

        $applicationService = $this->createMock(CartLineApplicationService::class);
        $applicationService->method('find')->willReturn($cartline);
        $applicationService->expects($this->once())->method('update');

        $queryBus = $this->getContainer()->get(QueryBus::class);
        $ensurements = new CartLinePersistEnsurements($queryBus);

        $useCase = new CartLineEditUseCase($ensurements, $applicationService);
        $command = new CartLineEditCommand(
            cartLineId: UuidValueObject::generate()->value(),
            ownerId: '123e4567-e89b-12d3-a456-426614174000',
            quantity: 2,
        );
        $useCase->__invoke($command);
    }

    public function testCartLineEditUseCaseOutOfStock(): void
    {
        $this->expectException(CartLineOutOfStockException::class);

        $cartline = CartLine::create([
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
            'cart_id' => '123e4567-e89b-12d3-a456-426614174001',
            'product_id' => '123e4567-e89b-12d3-a456-426614174002',
            'quantity' => 1,
        ]);

        $applicationService = $this->createMock(CartLineApplicationService::class);
        $applicationService->method('find')->willReturn($cartline);
        $applicationService->expects($this->never())->method('update');

        $queryBus = $this->getContainer()->get(QueryBus::class);
        $ensurements = new CartLinePersistEnsurements($queryBus);

        $useCase = new CartLineEditUseCase($ensurements, $applicationService);
        $command = new CartLineEditCommand(
            cartLineId: UuidValueObject::generate()->value(),
            ownerId: '123e4567-e89b-12d3-a456-426614174000',
            quantity: 2,
        );
        $useCase->__invoke($command);
    }

    public function testCartLineEditUseCaseError(): void
    {
        $this->expectException(CartLineNotFoundException::class);

        $queryBus = $this->getContainer()->get(QueryBus::class);
        $ensurements = new CartLinePersistEnsurements($queryBus);
        $applicationService = $this->createMock(CartLineApplicationService::class);
        $applicationService->method('find')->willReturn(null);

        $useCase = new CartLineEditUseCase($ensurements, $applicationService);
        $command = new CartLineEditCommand(
            cartLineId: UuidValueObject::generate()->value(),
            ownerId: '123e4567-e89b-12d3-a456-426614174000',
            quantity: 2,
        );
        $useCase->__invoke($command);
    }
}
