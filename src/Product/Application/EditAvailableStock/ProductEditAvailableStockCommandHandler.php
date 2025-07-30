<?php

declare(strict_types=1);

namespace App\Product\Application\EditAvailableStock;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class ProductEditAvailableStockCommandHandler
{
    public function __construct(
        private readonly ProductEditAvailableStockUseCase $useCase,
    ) {}

    public function __invoke(ProductEditAvailableStockCommand $command): void
    {
        $this->useCase->__invoke($command);
    }
}
