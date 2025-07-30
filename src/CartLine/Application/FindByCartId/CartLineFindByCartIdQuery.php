<?php

declare(strict_types=1);

namespace App\CartLine\Application\FindByCartId;

use App\_Shared\Message\Query\Domain\Query;

class CartLineFindByCartIdQuery implements Query
{
    public function __construct(
        public readonly string $cartId,
    ) {}
}
