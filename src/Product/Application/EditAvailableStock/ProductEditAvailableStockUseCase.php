<?php

declare(strict_types=1);

namespace App\Product\Application\EditAvailableStock;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductId;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\ProductApplicationService;

class ProductEditAvailableStockUseCase
{
    public function __construct(
        private readonly ProductApplicationService $applicationService,
    ) {}

    public function __invoke(ProductEditAvailableStockCommand $command): void
    {
        /** @var Product|null $product */
        $product = $this->applicationService->find(id: new ProductId($command->aggregateId()));
        if ($product === null) {
            throw new ProductNotFoundException($command->aggregateId());
        }

        $editProduct = Product::fromPrimitives([
            ...$product->toPrimitives(),
            'available_stock' => $product->availableStock() - $command->quantity,
        ]);

        $this->applicationService->update(product: $editProduct);
    }
}
