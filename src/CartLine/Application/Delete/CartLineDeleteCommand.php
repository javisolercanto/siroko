<?php

declare(strict_types=1);

namespace App\CartLine\Application\Delete;

use App\_Shared\Message\Command\Domain\Command;

final class CartLineDeleteCommand extends Command
{
    public function __construct(
        public readonly string $id,
        public readonly string $ownerId,
    ) {
        parent::__construct(aggregateId: $id);
    }

    public function toPrimitives(): array
    {
        return [
            'owner_id' => $this->ownerId,
        ];
    }

    public static function fromPrimitives(string $aggregateId, array $primitives): static
    {
        return new static(id: $aggregateId, ownerId: $primitives['owner_id']);
    }
}
