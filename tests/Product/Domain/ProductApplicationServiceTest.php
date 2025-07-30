<?php

declare(strict_types=1);

namespace App\Tests\Product\Domain;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductId;
use App\Product\Domain\ProductApplicationService;
use App\Product\Domain\ProductRepositoryInterface;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ProductApplicationServiceTest extends BaseTestCase
{
    private ProductApplicationService $applicationService;
    private ProductRepositoryInterface&MockObject $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(ProductRepositoryInterface::class);

        $this->applicationService = new ProductApplicationService(
            repository: $this->repository,
        );
    }

    public function testProductApplicationServiceFind(): void
    {
        $product = Product::create([
            'name' => 'Maillot',
            'price' => 50.0,
            'stock' => 10,
            'available_stock' => 10
        ]);

        $productId = new ProductId($product->id());

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($productId)
            ->willReturn($product);

        $result = $this->applicationService->find($productId);

        $this->assertSame($product, $result);
    }
}
