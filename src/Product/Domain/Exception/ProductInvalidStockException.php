<?php

declare(strict_types=1);

namespace App\Product\Domain\Exception;

class ProductInvalidStockException extends ProductException
{
    public function __construct(
        string $message = '',
    ) {
        parent::__construct('Invalid stock: ' . $message);
    }
}
