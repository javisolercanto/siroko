<?php

declare(strict_types=1);

namespace App\Product\Domain\Exception;

class ProductNotFoundException extends ProductException
{
    public function __construct(
        string $message = '',
    ) {
        parent::__construct('Product not found: ' . $message);
    }
}
