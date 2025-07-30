<?php

declare(strict_types=1);

namespace App\Cart\Application\Create;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\CartApplicationService;

class CartCreateUseCase
{
    public function __construct(
        private readonly CartApplicationService $applicationService,
    ) {}

    public function __invoke(CartCreateCommand $command): void
    {
        $cart = Cart::create($command->toPrimitives());

        $this->applicationService->save($cart);
    }
}
