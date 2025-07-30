<?php

declare(strict_types=1);

namespace App\Cart\Application\FindByOwnerId;

use App\_Shared\Message\Query\Domain\Query;

final class CartFindByOwnerIdQuery implements Query
{
    public function __construct(
        public readonly string $ownerId,
    ) {}
}
