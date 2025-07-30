<?php

declare(strict_types=1);

namespace App\CartLine\Application\Edit;

use App\CartLine\Application\_Shared\CartLinePersistEnsurements;
use App\CartLine\Domain\CartLineApplicationService;
use App\CartLine\Domain\Entity\CartLine;
use App\CartLine\Domain\Entity\CartLineId;
use App\CartLine\Domain\Exception\CartLineNotFoundException;
use App\CartLine\Domain\Exception\CartLineUnauthorizedException;

class CartLineEditUseCase
{
    public function __construct(
        private readonly CartLinePersistEnsurements $ensurements,
        private readonly CartLineApplicationService $applicationService,
    ) {}

    public function __invoke(CartLineEditCommand $command): void
    {
        /** @var CartLine|null $cartLine */
        $cartLine = $this->applicationService->find(new CartLineId($command->cartLineId));
        if ($cartLine === null) {
            throw new CartLineNotFoundException($command->cartLineId);
        }

        if ($cartLine->ownerId() !== $command->ownerId) {
            throw new CartLineUnauthorizedException('the user is not the owner of the cart line');
        }

        $this->ensurements->__invoke(
            ownerId: $command->ownerId,
            productId: $cartLine->productId(),
            quantity: $command->quantity,
        );

        $editCartLine = $cartLine->update([
            'quantity' => $command->quantity,
        ]);

        $this->applicationService->update($editCartLine);
    }
}
