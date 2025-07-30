<?php

declare(strict_types=1);

namespace App\Product\Infrastructure;

use App\_Shared\Infrastructure\RepositoryAdapter;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductId;
use App\Product\Domain\ProductRepositoryInterface;
use App\Product\Infrastructure\Persistence\ProductOrm;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends RepositoryAdapter<ProductOrm>
 */
class ProductDoctrineRepositoryAdapter extends RepositoryAdapter implements ProductRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager, ProductOrm::class);
    }

    public function find(ProductId $id): ?Product
    {
        /** @var ProductOrm|null */
        $product = $this->repository->find($id->value());
        if ($product === null) {
            return null;
        }

        return $this->toDomain($product);
    }

    public function findAll(): array
    {
        /** @var ProductOrm[] $products */
        $products = $this->repository->findAll();
        return array_map(fn(ProductOrm $product) => $this->toDomain($product), $products);
    }

    public function update(Product $product): Product
    {
        /** @var ProductOrm|null $reference */
        $reference = $this->repository->find($product->id());
        if ($reference === null) {
            throw new \Exception('Product not found');
        }

        $reference->merge($this->toPersistence($product));
        $this->entityManager->flush();

        return $this->toDomain($reference);
    }

    /**
     * Transform a ProductOrm object into a Product object.
     *
     * @param ProductOrm $object
     * @return Product
     */
    protected function toDomain(object $object): Product
    {
        return Product::fromPrimitives([
            'id' => $object->id(),
            'name' => $object->name(),
            'price' => $object->price(),
            'stock' => $object->stock(),
            'available_stock' => $object->availableStock(),
        ]);
    }

    /**
     * @codeCoverageIgnore
     *
     * Transform a Product object into a ProductOrm object.
     *
     * @param Product $object
     * @return ProductOrm
     */
    protected function toPersistence(object $object): ProductOrm
    {
        return new ProductOrm(
            id: $object->id(),
            name: $object->name(),
            price: $object->price(),
            stock: $object->stock(),
            availableStock: $object->availableStock(),
        );
    }
}
