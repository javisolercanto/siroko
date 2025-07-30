<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Application\Delete;

use App\_Shared\Message\AggregateRoot\Entity\UuidValueObject;
use App\CartLine\Application\Delete\CartLineDeleteCommand;
use App\CartLine\Application\Delete\CartLineDeleteUseCase;
use App\CartLine\Domain\CartLineApplicationService;
use App\CartLine\Domain\Entity\CartLine;
use App\CartLine\Domain\Exception\CartLineNotFoundException;
use App\Tests\BaseTestCase;

class CartLineDeleteUseCaseTest extends BaseTestCase
{
    public function testCartLineDeleteUseCase(): void
    {
        $cartline = CartLine::create([
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
            'cart_id' => '123e4567-e89b-12d3-a456-426614174001',
            'product_id' => '123e4567-e89b-12d3-a456-426614174002',
            'quantity' => 1,
        ]);

        $applicationService = $this->createMock(CartLineApplicationService::class);
        $applicationService->method('find')->willReturn($cartline);
        $applicationService->expects($this->once())->method('delete');

        $useCase = new CartLineDeleteUseCase($applicationService);
        $command = new CartLineDeleteCommand(
            id: UuidValueObject::generate()->value(),
            ownerId: '123e4567-e89b-12d3-a456-426614174000'
        );
        $useCase->__invoke($command);
    }

    public function testCartLineDeleteUseCaseError(): void
    {
        $this->expectException(CartLineNotFoundException::class);

        $applicationService = $this->createMock(CartLineApplicationService::class);
        $applicationService->method('find')->willReturn(null);

        $useCase = new CartLineDeleteUseCase($applicationService);
        $command = new CartLineDeleteCommand(
            id: UuidValueObject::generate()->value(),
            ownerId: '123e4567-e89b-12d3-a456-426614174000'
        );
        $useCase->__invoke($command);
    }
}