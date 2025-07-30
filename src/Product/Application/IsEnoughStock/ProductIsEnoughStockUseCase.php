<?php

declare(strict_types=1);

namespace App\Product\Application\IsEnoughStock;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductId;
use App\Product\Domain\ProductApplicationService;

class ProductIsEnoughStockUseCase
{
    public function __construct(
        private readonly ProductApplicationService $applicationService,
    ) {}

    public function __invoke(ProductIsEnoughStockQuery $query): bool
    {
        /** @var Product|null $product */
        $product = $this->applicationService->find(id: new ProductId($query->id));
        if ($product === null) {
            return false;
        }

        return $product->availableStock() >= $query->quantity;
    }
}
