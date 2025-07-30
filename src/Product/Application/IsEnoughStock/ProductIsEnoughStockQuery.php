<?php

declare(strict_types=1);

namespace App\Product\Application\IsEnoughStock;

use App\_Shared\Message\Query\Domain\Query;

final class ProductIsEnoughStockQuery implements Query
{
    public function __construct(
        public readonly string $id,
        public readonly int $quantity,
    ) {}
}
