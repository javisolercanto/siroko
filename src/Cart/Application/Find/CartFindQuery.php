<?php

declare(strict_types=1);

namespace App\Cart\Application\Find;

use App\_Shared\Message\Query\Domain\Query;

final class CartFindQuery implements Query
{
    public function __construct(
        public readonly string $id,
    ) {}
}
