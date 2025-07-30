<?php

declare(strict_types=1);

namespace App\Product\Domain\Entity;

use App\_Shared\Message\AggregateRoot\Entity\IntValueObject;
use App\Product\Domain\Exception\ProductInvalidAvailableStockException;

class ProductAvailableStock extends IntValueObject
{
    protected function _assert(mixed $value): void
    {
        if ($value < 0) {
            throw new ProductInvalidAvailableStockException('Available stock cannot be negative');
        }
    }
}
