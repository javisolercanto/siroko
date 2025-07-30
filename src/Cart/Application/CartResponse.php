<?php

declare(strict_types=1);

namespace App\Cart\Application;

use App\_Shared\Application\Response;

class CartResponse implements Response
{
    public function __construct(
        public readonly string $id,
        public readonly string $owner_id,
        public readonly bool $processed,
    ) {}
}
