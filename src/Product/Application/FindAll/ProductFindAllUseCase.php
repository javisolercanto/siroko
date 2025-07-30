<?php

declare(strict_types=1);

namespace App\Product\Application\FindAll;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\ProductApplicationService;

class ProductFindAllUseCase
{
    public function __construct(
        private readonly ProductApplicationService $applicationService,
    ) {}

    /**
     * @return Product[]
     */
    public function __invoke(ProductFindAllQuery $query): array
    {
        return $this->applicationService->findAll();
    }
}
