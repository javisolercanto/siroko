<?php

declare(strict_types=1);

namespace App\CartLine\Application;

use App\_Shared\Application\Response;

class CartLineResponse implements Response
{
    public function __construct(
        public readonly string $id,
        public readonly string $owner_id,
        public readonly string $cart_id,
        public readonly string $product_id,
        public readonly int $quantity,
    ) {}
}
