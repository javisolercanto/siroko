<?php

declare(strict_types=1);

namespace App\Tests\Cart\Application\Process;

use App\_Shared\Message\AggregateRoot\Entity\UuidValueObject;
use App\Cart\Application\Process\CartProcessCommand;
use App\Cart\Application\Process\CartProcessUseCase;
use App\Cart\Domain\CartApplicationService;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Exception\CartNotFoundException;
use App\Tests\BaseTestCase;

class CartProcessUseCaseTest extends BaseTestCase
{
    public function testCartProcessUseCase(): void
    {
        $cart = Cart::create([
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
        ]);

        $applicationService = $this->createMock(CartApplicationService::class);
        $applicationService->method('find')->willReturn($cart);
        $applicationService->expects($this->once())->method('update');

        $useCase = new CartProcessUseCase($applicationService);
        $command = new CartProcessCommand(
            cartId: UuidValueObject::generate()->value(),
        );
        $useCase->__invoke($command);
    }

    public function testCartProcessUseCaseError(): void
    {
        $this->expectException(CartNotFoundException::class);

        $applicationService = $this->createMock(CartApplicationService::class);
        $applicationService->method('find')->willReturn(null);

        $useCase = new CartProcessUseCase($applicationService);
        $command = new CartProcessCommand(
            cartId: UuidValueObject::generate()->value(),
        );
        $useCase->__invoke($command);
    }
}
