<?php

declare(strict_types=1);

namespace App\Product\Domain;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductId;

interface ProductRepositoryInterface {
    /**
     * Find product by id if not found return null
     *
     * @param ProductId $id
     * @return ?Product
     */
    public function find(ProductId $id): ?Product;

    /**
     * Find all products
     *
     * @return Product[]
     */
    public function findAll(): array;

    /**
     * Update product
     *
     * @param Product $product
     * @return Product
     */
    public function update(Product $product): Product;
}
