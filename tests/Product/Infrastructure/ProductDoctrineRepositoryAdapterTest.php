<?php

declare(strict_types=1);

namespace App\Tests\Product\Infrastructure;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductId;
use App\Product\Infrastructure\Persistence\ProductOrm;
use App\Product\Infrastructure\ProductDoctrineRepositoryAdapter;
use App\Tests\BaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\MockObject;

class ProductDoctrineRepositoryAdapterTest extends BaseTestCase
{
    private EntityManagerInterface&MockObject $entityManager;
    private EntityRepository&MockObject $repositoryMock;
    private ProductDoctrineRepositoryAdapter $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repositoryMock = $this->createMock(EntityRepository::class);

        $this->entityManager
            ->method('getRepository')
            ->with(ProductOrm::class)
            ->willReturn($this->repositoryMock);

        $this->repository = new ProductDoctrineRepositoryAdapter($this->entityManager);
    }


    public function testProductDoctrineRepositoryAdapterFind(): void
    {
        $id = ProductId::generate();

        $expected = Product::fromPrimitives([
            'id' => $id->value(),
            'name' => 'Maillot',
            'price' => 50.0,
            'stock' => 10,
            'available_stock' => 10
        ]);

        $expectedOrm = new ProductOrm(
            id: $expected->id(),
            name: $expected->name(),
            price: $expected->price(),
            stock: $expected->stock(),
            availableStock: $expected->availableStock(),
        );

        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id->value())
            ->willReturn($expectedOrm);

        $product = $this->repository->find($id);

        $this->assertEquals($expected, $product);
    }

    public function testProductDoctrineRepositoryAdapterFindNotFound(): void
    {
        $id = ProductId::generate();

        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id->value())
            ->willReturn(null);

        $product = $this->repository->find($id);

        $this->assertNull($product);
    }
}
