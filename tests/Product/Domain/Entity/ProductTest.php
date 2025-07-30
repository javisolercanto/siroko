<?php

declare(strict_types=1);

namespace App\Tests\Product\Domain\Entity;

use App\Product\Domain\Entity\Product;
use App\Tests\BaseTestCase;

class ProductTest extends BaseTestCase
{
    public function testProductCreate(): void
    {
        $primitives = [
            'name' => 'Maillot',
            'price' => 50.0,
            'stock' => 10,
            'available_stock' => 10
        ];

        $product = Product::create($primitives);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertNotNull($product->id());
        $this->assertEquals($primitives['name'], $product->name());
        $this->assertEquals($primitives['price'], $product->price());
        $this->assertEquals($primitives['stock'], $product->stock());
        $this->assertEquals($primitives['available_stock'], $product->availableStock());
    }

    public function testProductPrimitives(): void
    {
        $primitives = [
            'id' => '0198405b-4f03-737c-950d-d14e39290e5c',
            'name' => 'Maillot',
            'price' => 50.0,
            'stock' => 10,
            'available_stock' => 10
        ];

        $product = Product::fromPrimitives($primitives);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($primitives['id'], $product->id());
        $this->assertEquals($primitives['name'], $product->name());
        $this->assertEquals($primitives['price'], $product->price());
        $this->assertEquals($primitives['stock'], $product->stock());
        $this->assertEquals($primitives['available_stock'], $product->availableStock());

        $this->assertEquals($primitives, $product->toPrimitives());
    }
}