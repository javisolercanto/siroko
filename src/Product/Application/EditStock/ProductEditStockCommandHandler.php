<?php

declare(strict_types=1);

namespace App\Product\Application\EditStock;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class ProductEditStockCommandHandler
{
    public function __construct(
        private readonly ProductEditStockUseCase $useCase,
    ) {}

    public function __invoke(ProductEditStockCommand $command): void
    {
        $this->useCase->__invoke($command);
    }
}
