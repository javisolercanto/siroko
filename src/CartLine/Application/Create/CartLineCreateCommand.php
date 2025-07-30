<?php

declare(strict_types=1);

namespace App\CartLine\Application\Create;

use App\_Shared\Message\Command\Domain\Command;

final class CartLineCreateCommand extends Command
{
    public function __construct(
        public readonly string $ownerId,
        public readonly string $cartId,
        public readonly string $productId,
        public readonly int $quantity,
    ) {
        parent::__construct(aggregateId: '');
    }

    public function toPrimitives(): array
    {
        return [
            'owner_id' => $this->ownerId,
            'cart_id' => $this->cartId,
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
        ];
    }

    public static function fromPrimitives(string $aggregateId, array $primitives): static
    {
        return new static(
            ownerId: $primitives['owner_id'],
            cartId: $primitives['cart_id'],
            productId: $primitives['product_id'],
            quantity: $primitives['quantity'],
        );
    }
}
