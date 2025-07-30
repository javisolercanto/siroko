<?php

declare(strict_types=1);

namespace App\Product\Domain;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductId;

class ProductApplicationService implements ProductRepositoryInterface
{
    public function __construct(
        private readonly ProductRepositoryInterface $repository,
    ) {}

    public function find(ProductId $id): ?Product
    {
        return $this->repository->find(id: $id);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function update(Product $product): Product
    {
        return $this->repository->update(product: $product);
    }
}
