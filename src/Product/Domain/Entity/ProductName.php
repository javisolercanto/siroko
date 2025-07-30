<?php

declare(strict_types=1);

namespace App\Product\Domain\Entity;

use App\_Shared\Message\AggregateRoot\Entity\StringValueObject;
use App\Product\Domain\Exception\ProductInvalidNameException;

class ProductName extends StringValueObject
{

    protected function _assert(mixed $value): void
    {
        if (strlen($value) < 3) {
            throw new ProductInvalidNameException('Name too short');
        }

        if (strlen($value) > 30) {
            throw new ProductInvalidNameException('Name too long');
        }
    }
}
