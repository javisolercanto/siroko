<?php

declare(strict_types=1);

namespace App\Order\Application;

use App\_Shared\Application\Response;

class OrderResponse implements Response
{
    public function __construct(
        public readonly string $id,
        public readonly string $owner_id,
        public readonly string $cart_id,
        public readonly string $date,
    ) {}
}
