<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\EditStock;

use App\_Shared\Message\AggregateRoot\Entity\UuidValueObject;
use App\Product\Application\EditStock\ProductEditStockCommand;
use App\Product\Application\EditStock\ProductEditStockUseCase;
use App\Product\Domain\ProductApplicationService;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Tests\BaseTestCase;

class ProductEditStockUseCaseTest extends BaseTestCase
{
    public function testProductEditStockUseCase(): void
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

        $useCase = new ProductEditStockUseCase($applicationService);
        $command = new ProductEditStockCommand(
           productId: $product->id(),
           quantity: 1,
        );
        $useCase->__invoke($command);
    }

    public function testProductEditStockUseCaseError(): void
    {
        $this->expectException(ProductNotFoundException::class);

        $applicationService = $this->createMock(ProductApplicationService::class);
        $applicationService->method('find')->willReturn(null);

        $useCase = new ProductEditStockUseCase($applicationService);
        $command = new ProductEditStockCommand(
            productId: UuidValueObject::generate()->value(),
            quantity: 1,
        );
        $useCase->__invoke($command);
    }
}