<?php

declare(strict_types=1);

namespace App\Cart\Application\Create;

use App\_Shared\Message\Command\Domain\Command;

final class CartCreateCommand extends Command
{
    public function __construct(
        public readonly string $ownerId,
    ) {
        parent::__construct(aggregateId: '');
    }

    public function toPrimitives(): array
    {
        return [
            'owner_id' => $this->ownerId,
        ];
    }

    public static function fromPrimitives(string $aggregateId, array $primitives): static
    {
        return new static(
            ownerId: $primitives['owner_id'],
        );
    }
}
