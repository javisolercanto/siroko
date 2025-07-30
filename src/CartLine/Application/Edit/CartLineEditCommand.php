<?php

declare(strict_types=1);

namespace App\CartLine\Application\Edit;

use App\_Shared\Message\Command\Domain\Command;

final class CartLineEditCommand extends Command
{
    public function __construct(
        public readonly string $cartLineId,
        public readonly string $ownerId,
        public readonly int $quantity,
    ) {
        parent::__construct(aggregateId: $cartLineId);
    }

    public function toPrimitives(): array
    {
        return [
            'owner_id' => $this->ownerId,
            'quantity' => $this->quantity,
        ];
    }

    public static function fromPrimitives(string $aggregateId, array $primitives): static
    {
        return new static(
            cartLineId: $aggregateId,
            ownerId: $primitives['owner_id'],
            quantity: $primitives['quantity'],
        );
    }
}
