<?php

declare(strict_types=1);

namespace App\CartLine\Domain\Entity;

use App\_Shared\Message\AggregateRoot\Entity\IntValueObject;
use App\CartLine\Domain\Exception\CartLineInvalidQuantityException;

class CartLineQuantity extends IntValueObject
{
    protected function _assert(mixed $value): void
    {
        if ($value < 1) {
            throw new CartLineInvalidQuantityException('Quantity must be greater than 0');
        }
    }
}
