<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\EditAvailableStock;

use App\_Shared\Message\AggregateRoot\Entity\UuidValueObject;
use App\Product\Application\EditAvailableStock\ProductEditAvailableStockCommand;
use App\Product\Application\EditAvailableStock\ProductEditAvailableStockUseCase;
use App\Product\Domain\ProductApplicationService;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Tests\BaseTestCase;

class ProductEditAvailableStockUseCaseTest extends BaseTestCase
{
    public function testProductEditAvailableStockUseCase(): void
    {
        $product = Product::create([
            'id' => UuidValueObject::generate(),
            'name' => 'Product',
            'price' => 50.0,
            'stock' => 10,
            'available_stock' => 10,
        ]);

        $applicationService = $this->createMock(ProductApplicationService::class);
        $applicationService->method('find')->willReturn($product);
        $applicationService->expects($this->once())->method('update');

        $useCase = new ProductEditAvailableStockUseCase($applicationService);
        $command = new ProductEditAvailableStockCommand(
           productId: $product->id(),
           quantity: 1,
        );
        $useCase->__invoke($command);
    }

    public function testProductEditAvailableStockUseCaseError(): void
    {
        $this->expectException(ProductNotFoundException::class);

        $applicationService = $this->createMock(ProductApplicationService::class);
        $applicationService->method('find')->willReturn(null);

        $useCase = new ProductEditAvailableStockUseCase($applicationService);
        $command = new ProductEditAvailableStockCommand(
            productId: UuidValueObject::generate()->value(),
            quantity: 1,
        );
        $useCase->__invoke($command);
    }
}