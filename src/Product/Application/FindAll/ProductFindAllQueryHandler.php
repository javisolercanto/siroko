<?php

declare(strict_types=1);

namespace App\Product\Application\FindAll;

use App\Product\Application\ProductResponse;
use App\Product\Application\ProductResponseConverter;
use App\Product\Domain\Entity\Product;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class ProductFindAllQueryHandler
{
    public function __construct(
        private readonly ProductFindAllUseCase $useCase,
        private readonly ProductResponseConverter $converter,
    ) {}

    /**
     * @return ProductResponse[]
     */
    public function __invoke(ProductFindAllQuery $query): array
    {
        /** @var Product[] $products */
        $products = $this->useCase->__invoke($query);

        return array_map(fn(Product $product) => $this->converter->__invoke(product: $product), $products);
    }
}
