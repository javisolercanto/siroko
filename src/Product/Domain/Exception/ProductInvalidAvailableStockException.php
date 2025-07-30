<?php

declare(strict_types=1);

namespace App\Product\Domain\Exception;

class ProductInvalidAvailableStockException extends ProductException
{
    public function __construct(
        string $message = '',
    ) {
        parent::__construct('Invalid avaibale stock: ' . $message);
    }
}
