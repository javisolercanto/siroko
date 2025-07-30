<?php

declare(strict_types=1);

namespace App\Product\Application\Find;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductId;
use App\Product\Domain\ProductApplicationService;

class ProductFindUseCase
{
    public function __construct(
        private readonly ProductApplicationService $applicationService,
    ) {}

    public function __invoke(ProductFindQuery $query): ?Product
    {
        return $this->applicationService->find(id: new ProductId($query->id));
    }
}
