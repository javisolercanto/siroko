<?php

declare(strict_types=1);

namespace App\Product\Application;

use App\_Shared\Application\Response;

class ProductResponse implements Response
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly float $price,
        public readonly int $stock,
        public readonly int $available_stock,
    ) {}
}
