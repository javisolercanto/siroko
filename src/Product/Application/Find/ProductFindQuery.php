<?php

declare(strict_types=1);

namespace App\Product\Application\Find;

use App\_Shared\Message\Query\Domain\Query;

final class ProductFindQuery implements Query
{
    public function __construct(
        public readonly string $id,
    ) {}
}
