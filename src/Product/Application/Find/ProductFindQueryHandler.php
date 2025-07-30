<?php

declare(strict_types=1);

namespace App\Product\Application\Find;

use App\Product\Application\ProductResponse;
use App\Product\Application\ProductResponseConverter;
use App\Product\Domain\Entity\Product;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class ProductFindQueryHandler
{
    public function __construct(
        private readonly ProductFindUseCase $useCase,
        private readonly ProductResponseConverter $converter,
    ) {}

    public function __invoke(ProductFindQuery $query): ?ProductResponse
    {
        /** @var Product|null $product */
        $product = $this->useCase->__invoke($query);
        if ($product === null) {
            return null;
        }

        return $this->converter->__invoke(product: $product);
    }
}
