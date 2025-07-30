<?php

declare(strict_types=1);

namespace App\Cart\Domain\Exception;

class CartNotFoundException extends CartException
{
    public function __construct(
        string $message = '',
    ) {
        parent::__construct('Cart not found: ' . $message);
    }
}
