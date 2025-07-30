<?php

declare(strict_types=1);

namespace App\Product\Domain\Entity;

use App\_Shared\Message\AggregateRoot\Entity\AggregateRoot;

class Product extends AggregateRoot
{
    final public function __construct(
        ProductId $id,
        private readonly ProductName $name,
        private readonly ProductPrice $price,
        private readonly ProductStock $stock,
        private readonly ProductAvailableStock $availableStock,
    ) {
        parent::__construct(id: $id);
    }

    final public static function create(array $primitives): static
    {
        return new static(
            id: ProductId::generate(),
            name: new ProductName($primitives['name']),
            price: new ProductPrice($primitives['price']),
            stock: new ProductStock($primitives['stock']),
            availableStock: new ProductAvailableStock($primitives['available_stock']),
        );
    }

    final public static function fromPrimitives(array $primitives): static
    {
        return new static(
            id: new ProductId($primitives['id']),
            name: new ProductName($primitives['name']),
            price: new ProductPrice($primitives['price']),
            stock: new ProductStock($primitives['stock']),
            availableStock: new ProductAvailableStock($primitives['available_stock']),
        );
    }

    final public function toPrimitives(): array
    {
        return [
            'id' => $this->id->value(),
            'name' => $this->name->value(),
            'price' => $this->price->value(),
            'stock' => $this->stock->value(),
            'available_stock' => $this->availableStock->value(),
        ];
    }

    final public function name(): string
    {
        return $this->name->value();
    }

    final public function price(): float
    {
        return $this->price->value();
    }

    final public function stock(): int
    {
        return $this->stock->value();
    }

    final public function availableStock(): int
    {
        return $this->availableStock->value();
    }
}
