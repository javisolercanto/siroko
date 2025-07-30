<?php

declare(strict_types=1);

namespace App\Cart\Application\Process;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\CartApplicationService;
use App\Cart\Domain\Entity\CartId;
use App\Cart\Domain\Exception\CartNotFoundException;

class CartProcessUseCase
{
    public function __construct(
        private readonly CartApplicationService $applicationService,
    ) {}

    public function __invoke(CartProcessCommand $command): void
    {
        /** @var Cart|null $cart */
        $cart = $this->applicationService->find(new CartId($command->aggregateId()));
        if ($cart === null) {
            throw new CartNotFoundException();
        }

        $editCart = Cart::fromPrimitives([
            ...$cart->toPrimitives(),
            'processed' => true,
        ]);

        $this->applicationService->update($editCart);
    }
}
