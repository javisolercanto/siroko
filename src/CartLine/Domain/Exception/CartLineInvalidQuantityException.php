<?php

declare(strict_types=1);

namespace App\CartLine\Domain\Exception;

class CartLineInvalidQuantityException extends CartLineException
{
    public function __construct(
        string $message = '',
    ) {
        parent::__construct('Cart line invalid quantity: ' . $message);
    }
}
