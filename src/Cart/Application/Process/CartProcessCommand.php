<?php

declare(strict_types=1);

namespace App\Cart\Application\Process;

use App\_Shared\Message\Command\Domain\Command;

final class CartProcessCommand extends Command
{
    public function __construct(
        string $cartId,
    ) {
        parent::__construct(aggregateId: $cartId);
    }

    public function toPrimitives(): array
    {
        return [];
    }

    public static function fromPrimitives(string $aggregateId, array $primitives): static
    {
        return new static(cartId: $aggregateId);
    }
}
