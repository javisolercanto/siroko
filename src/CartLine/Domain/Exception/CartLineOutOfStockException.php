<?php

declare(strict_types=1);

namespace App\CartLine\Domain\Exception;

class CartLineOutOfStockException extends CartLineException
{
    public function __construct(
        string $message = '',
    ) {
        parent::__construct('Cart line out of stock: ' . $message);
    }
}
