<?php

declare(strict_types=1);

namespace App\Product\Application\IsEnoughStock;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class ProductIsEnoughStockQueryHandler
{
    public function __construct(
        private readonly ProductIsEnoughStockUseCase $useCase,
    ) {}

    public function __invoke(ProductIsEnoughStockQuery $query): bool
    {
        return $this->useCase->__invoke($query);
    }
}
