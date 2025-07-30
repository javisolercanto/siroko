<?php

declare(strict_types=1);

namespace App\Product\Domain\Entity;

use App\_Shared\Message\AggregateRoot\Entity\FloatValueObject;
use App\Product\Domain\Exception\ProductInvalidPriceException;

class ProductPrice extends FloatValueObject
{
    protected function _assert(mixed $value): void
    {
        if ($value < 0) {
            throw new ProductInvalidPriceException('Price cannot be negative');
        }
    }
}
