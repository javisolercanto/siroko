<?php

declare(strict_types=1);

namespace App\Product\Application\EditStock;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductId;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\ProductApplicationService;

class ProductEditStockUseCase
{
    public function __construct(
        private readonly ProductApplicationService $applicationService,
    ) {}

    public function __invoke(ProductEditStockCommand $command): void
    {
        /** @var Product|null $product */
        $product = $this->applicationService->find(id: new ProductId($command->aggregateId()));
        if ($product === null) {
            throw new ProductNotFoundException($command->aggregateId());
        }

        $editProduct = Product::fromPrimitives([
            ...$product->toPrimitives(),
            'stock' => $product->stock() - $command->quantity,
        ]);

        $this->applicationService->update(product: $editProduct);
    }
}
