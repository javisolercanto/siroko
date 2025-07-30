<?php

declare(strict_types=1);

namespace App\Order\Application\Create;

use App\_Shared\Message\Command\Domain\Command;

final class OrderCreateCommand extends Command
{
    public function __construct(
        public readonly string $ownerId,
        public readonly string $cartId,
    ) {
        parent::__construct(aggregateId: '');
    }

    public function toPrimitives(): array
    {
        return [
            'owner_id' => $this->ownerId,
            'cart_id' => $this->cartId,
        ];
    }

    public static function fromPrimitives(string $aggregateId, array $primitives): static
    {
        return new static(
            ownerId: $primitives['owner_id'],
            cartId: $primitives['cart_id'],
        );
    }
}
