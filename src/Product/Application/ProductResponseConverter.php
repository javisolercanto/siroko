<?php

declare(strict_types=1);

namespace App\Product\Application;

use App\Product\Domain\Entity\Product;

class ProductResponseConverter
{
    public function __invoke(Product $product): ProductResponse
    {
        return new ProductResponse(
            id: $product->id(),
            name: $product->name(),
            price: $product->price(),
            stock: $product->stock(),
            available_stock: $product->availableStock(),
        );
    }
}
