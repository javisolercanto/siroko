<?php

declare(strict_types=1);

namespace App\Tests\Product\Infrastructure;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductId;
use App\Product\Infrastructure\ProductDoctrineRepositoryAdapter;
use App\Tests\BaseTestCase;

class ProductDoctrineRepositoryAdapterIntegrationTest extends BaseTestCase
{
    private ProductDoctrineRepositoryAdapter $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new ProductDoctrineRepositoryAdapter($this->em);
    }

    public function testProductDoctrineRepositoryAdapterIntegrationFind(): void
    {
        $id = '019850bc-0cc9-74f3-886f-ff13031f2fc8';

        $found = $this->repository->find(new ProductId($id));

        $this->assertNotNull($found);
        $this->assertSame($id, $found->id());
        $this->assertInstanceOf(Product::class, $found);
    }

    public function testProductDoctrineRepositoryAdapterIntegrationFindError(): void
    {
        $id = '01984fd5-150d-7309-8816-eb1b106262ef';

        $found = $this->repository->find(new ProductId($id));

        $this->assertNull($found);
    }

    public function testProductDoctrineRepositoryAdapterIntegrationUpdate(): void
    {
        $product = Product::fromPrimitives([
            'id' => '019850bc-0cc9-74f3-886f-ff13031f2fc8',
            'name' => 'Maillot',
            'price' => 50.0,
            'stock' => 10,
            'available_stock' => 1,
        ]);

        $updated = $this->repository->update($product);

        $this->assertNotNull($updated);
        $this->assertSame($product->id(), $updated->id());
        $this->assertSame($product->name(), $updated->name());
        $this->assertSame($product->price(), $updated->price());
        $this->assertSame($product->stock(), $updated->stock());
        $this->assertSame($product->availableStock(), $updated->availableStock());
        $this->assertInstanceOf(Product::class, $updated);
    }

    public function testProductDoctrineRepositoryAdapterIntegrationUpdateError(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Product not found');

        $product = Product::fromPrimitives([
            'id' => '019855ee-74c0-73a8-83b0-0b91ee64342c',
            'name' => 'Maillot',
            'price' => 50.0,
            'stock' => 10,
            'available_stock' => 1,
        ]);

        $this->repository->update($product);
    }
}
