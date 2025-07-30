<?php

declare(strict_types=1);

namespace App\Order\Application\Find;

use App\_Shared\Message\Query\Domain\Query;

final class OrderFindByOwnerIdQuery implements Query
{
    public function __construct(
        public readonly string $ownerId,
    ) {}
}
