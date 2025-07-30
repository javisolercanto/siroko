<?php

declare(strict_types=1);

namespace App\CartLine\Application\Delete;

use App\_Shared\Domain\DomainEvents\CartLineDeletedDomainEvent;
use App\_Shared\Domain\DomainEvents\CartLineUpdatedDomainEvent;
use App\CartLine\Domain\CartLineApplicationService;
use App\CartLine\Domain\Entity\CartLine;
use App\CartLine\Domain\Entity\CartLineId;
use App\CartLine\Domain\Exception\CartLineNotFoundException;
use App\CartLine\Domain\Exception\CartLineUnauthorizedException;

class CartLineDeleteUseCase
{
    public function __construct(
        private readonly CartLineApplicationService $applicationService,
    ) {}

    public function __invoke(CartLineDeleteCommand $command): void
    {
        /** @var CartLine|null $cartLine */
        $cartLine = $this->applicationService->find(new CartLineId($command->id));
        if ($cartLine === null) {
            throw new CartLineNotFoundException($command->id);
        }

        if ($cartLine->ownerId() !== $command->ownerId) {
            throw new CartLineUnauthorizedException($command->id);
        }

        $event = new CartLineDeletedDomainEvent(
            cartLineId: $cartLine->id(),
            productId: $cartLine->productId(),
            quantity: $cartLine->quantity(),
        );

        $cartLine->record($event);

        $this->applicationService->delete($cartLine);
    }
}
