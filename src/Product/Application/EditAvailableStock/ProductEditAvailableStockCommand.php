<?php

declare(strict_types=1);

namespace App\Product\Application\EditAvailableStock;

use App\_Shared\Message\Command\Domain\Command;

final class ProductEditAvailableStockCommand extends Command
{
    public function __construct(
        string $productId,
        public readonly int $quantity,
    ) {
        parent::__construct(aggregateId: $productId);
    }

    public function toPrimitives(): array
    {
        return [
            'quantity' => $this->quantity,
        ];
    }


    public static function fromPrimitives(string $aggregateId, array $primitives): static
    {
        return new static(
            productId: $aggregateId,
            quantity: $primitives['quantity'],
        );
    }
}
